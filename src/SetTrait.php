<?php


namespace Uginroot\PhpSet;


use Generator;
use ReflectionClass;
use Uginroot\PhpSet\Exception\DuplicateValueException;
use Uginroot\PhpSet\Exception\IncorrectNameException;
use Uginroot\PhpSet\Exception\IncorrectValueException;

trait SetTrait
{
    /**
     * @var mixed[] Constants cache list by all children class
     */
    private static array $valueCache = [];

    /**
     * @var mixed[] Values current Set object
     */
    private array $values = [];

    /**
     * Init constants list by current static class
     */
    final private static function init(): void
    {
        if (array_key_exists(static::class, self::$valueCache)) {
            return;
        }
        $values = [];

        $class = new ReflectionClass(static::class);
        foreach ($class->getReflectionConstants() as $constant) {
            $value = $constant->getValue();

            if (in_array($value, $values, true)) {
                throw new DuplicateValueException("Value $value duplicate in class {$class->getName()}");
            }

            $values[$constant->getName()] = $value;
        }

        static::$valueCache[static::class] = $values;
    }

    /**
     * @return array|string[]
     */
    final public static function getNameVariants(): array
    {
        static::init();
        return array_keys(self::$valueCache[static::class]);
    }

    /**
     * @return array|mixed[]
     */
    final public static function getValueVariants(): array
    {
        static::init();
        return array_values(self::$valueCache[static::class]);
    }

    /**
     * @param mixed $value
     * @return string
     */
    final public static function getValueName($value): string
    {
        static::init();
        $name = array_search($value, static::$valueCache[static::class], true);
        if ($name === null) {
            throw new IncorrectValueException("Value '{$value}' not in constants list of class " . static::class);
        }
        return $name;
    }

    /**
     * @param string $name
     * @return mixed
     */
    final public static function getNameValue(string $name)
    {
        static::init();
        if (!key_exists($name, static::$valueCache[static::class])) {
            throw new IncorrectNameException("Constant '{$name}' not in class " . static::class);
        }
        return static::$valueCache[static::class][$name];
    }

    /**
     * @param mixed $values
     * @return Generator
     */
    final private static function valueGenerator($values)
    {
        if (is_iterable($values)) {
            foreach ($values as $value) {
                foreach (static::valueGenerator($value) as $subValue) {
                    yield $subValue;
                }
            }
        } elseif ($values instanceof static) {
            foreach ($values->getValues() as $value) {
                yield $value;
            }
        } else {
            yield $values;
        }
    }

    /**
     * @param mixed $values
     * @return array|mixed[]
     */
    final private static function valueExtractor($values): array
    {
        $valuesResult = [];
        foreach (static::valueGenerator($values) as $value) {
            $valuesResult[] = $value;
        }
        $valuesResult = array_unique($valuesResult);
        return $valuesResult;
    }

    /**
     * @param $names
     * @return Generator
     */
    final private static function nameGenerator($names){
        if(is_iterable($names)){
            foreach ($names as $name){
                foreach (static::nameGenerator($name) as $subName){
                    yield $subName;
                }
            }
        } else {
            yield $names;
        }
    }

    /**
     * @return array|string[]
     */
    final public function getNames(): array
    {
        return array_keys($this->values);
    }

    /**
     * @return array|mixed[]
     */
    final public function getValues(): array
    {
        return array_values($this->values);
    }

    /**
     * @param mixed ...$values
     * @return bool
     */
    final public function in(...$values): bool
    {
        foreach (static::valueGenerator($values) as $value) {
            if(in_array($value, $this->values, true)){
                return true;
            }
        }
        return false;
    }

    /**
     * @param mixed ...$values
     * @return bool
     */
    final public function is(...$values): bool
    {
        foreach (static::valueGenerator($values) as $value){
            if(!in_array($value, $this->values, true)){
                return false;
            }
        }
        return true;
    }

    /**
     * @param mixed ...$values
     * @return bool
     */
    final public function equal(...$values): bool
    {
        $countCheck = 0;
        foreach (static::valueExtractor($values) as $value){
            if(!in_array($value, $this->values, true)){
                return false;
            }
            $countCheck++;
        }
        return $countCheck === count($this->values);
    }


    /**
     * @param static $self
     * @param $value
     * @return $this
     */
    final private static function addObjectValue(self $self, $value): self
    {
        $self->values[static::getValueName($value)] = $value;
        return $self;
    }

    /**
     * @param static $self
     * @param $value
     * @return $this
     */
    final private static function removeObjectValue(self $self, $value): self
    {
        unset($self->values[static::getValueName($value)]);
        return $self;
    }

    /**
     * @param $self
     * @param mixed ...$values
     * @return $this
     */
    final private static function setObjectValues(self $self, ...$values): self
    {
        $self->values = [];
        foreach (static::valueExtractor($values) as $value) {
            static::addObjectValue($self, $value);
        }
        return $self;
    }

    /**
     * @param SetTrait $self
     * @param mixed ...$names
     * @return $this
     */
    final private static function setObjectNames(self $self, ...$names): self
    {
        $self->values = [];
        foreach (static::nameGenerator($names) as $name){
            static::addObjectValueByName($self, $name);
        }

        return $self;
    }

    /**
     * @param SetTrait $self
     * @param string $name
     * @return static
     */
    final private static function addObjectValueByName(self $self, string $name): self
    {
        $self->values[$name] = static::getNameValue($name);
        return $self;
    }


    /**
     * @param SetTrait $self
     * @param string $name
     * @return $this
     */
    final private static function removeObjectValueByName(self $self, string $name): self
    {
        static::getNameValue($name);
        unset($self->values[$name]);
        return $self;
    }
}