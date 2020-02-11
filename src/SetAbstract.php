<?php


namespace Uginroot\PhpSet;


use Generator;
use ReflectionClass;
use Uginroot\PhpSet\Exception\DuplicateValueException;
use Uginroot\PhpSet\Exception\IncorrectValueException;

abstract class SetAbstract implements SetInterface
{
    private static array $valueCache = [];

    private array $values = [];

    private static function init(): void
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
    public static function getNameVariants(): array
    {
        static::init();
        return array_keys(self::$valueCache[static::class]);
    }

    /**
     * @return array|mixed[]
     */
    public static function getValueVariants(): array
    {
        static::init();
        return array_values(self::$valueCache[static::class]);
    }

    /**
     * @param mixed $value
     * @return string
     */
    public static function getValueName($value): string
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
    public static function getNameValue(string $name)
    {
        static::init();
        if (!key_exists($name, static::$valueCache[static::class])) {
            throw new IncorrectValueException("Constant '{$name}' not in class " . static::class);
        }
        return static::$valueCache[static::class][$name];
    }

    /**
     * @param mixed $values
     * @return Generator
     */
    private static function valueGenerator($values)
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
    private static function valueExtractor($values): array
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
    private static function nameGenerator($names){
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
     * SetAbstract constructor.
     * @param mixed ...$values
     */
    public function __construct(...$values)
    {
        $this->setValues(...$values);
    }

    /**
     * @param $value
     * @return $this
     */
    final public function addValue($value): self
    {
        $this->values[static::getValueName($value)] = $value;
        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    final public function removeValue($value): self
    {
        unset($this->values[static::getValueName($value)]);
        return $this;
    }

    /**
     * @param mixed ...$values
     * @return $this
     */
    final public function setValues(...$values): self
    {
        $this->values = [];
        foreach (static::valueExtractor($values) as $value) {
            $this->addValue($value);
        }
        return $this;
    }

    final public function setNames(...$names): self
    {
        $this->values = [];
        foreach (static::nameGenerator($names) as $name){
            $this->addValueByName($name);
        }

        return $this;
    }

    final public function addValueByName(string $name): self
    {
        $this->values[$name] = static::getNameValue($name);
        return $this;
    }

    final public function removeValueByName(string $name): self
    {
        static::getNameValue($name);
        unset($this->values[$name]);
        return $this;
    }

    /**
     * @return array|string[]
     */
    public function getNames(): array
    {
        return array_keys($this->values);
    }

    /**
     * @return array|mixed[]
     */
    public function getValues(): array
    {
        return array_values($this->values);
    }

    public function in(...$values): bool
    {
        foreach (static::valueGenerator($values) as $value) {
            if(in_array($value, $this->values, true)){
                return true;
            }
        }
        return false;
    }

    public function is(...$values): bool
    {
        foreach (static::valueGenerator($values) as $value){
            if(!in_array($value, $this->values, true)){
                return false;
            }
        }
        return true;
    }

    public function equal(...$values): bool
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
}