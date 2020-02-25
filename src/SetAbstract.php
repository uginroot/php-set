<?php


namespace Uginroot\PhpSet;

use Generator;
use ReflectionClass;
use Uginroot\PhpSet\Exception\DuplicateValueException;
use Uginroot\PhpSet\Exception\IncorrectNameException;
use Uginroot\PhpSet\Exception\IncorrectValueException;

class SetAbstract implements SetInterface
{
    /**
     * @var array|mixed[]
     */
    private array $values = [];

    /**
     * @var mixed[] Constants cache list by all children class
     */
    private static array $valueCache = [];

    /**
     * Init constants list by current static class
     */
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
    public static function getName($value): string
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
    public static function getValue(string $name)
    {
        static::init();
        if (!key_exists($name, static::$valueCache[static::class])) {
            throw new IncorrectNameException("Constant '{$name}' not in class " . static::class);
        }
        return static::$valueCache[static::class][$name];
    }

    /**
     * @param mixed[] ...$names
     * @return static
     */
    public static function createFromNames(...$names): self
    {
        $self = new static();
        $self->thisAddNames(...$names);
        return $self;
    }

    /**
     * SetAbstract constructor.
     * @param mixed ...$values
     */
    public function __construct(...$values)
    {
        $this->thisSetValues(...$values);
    }

    /**
     * @param mixed[] $values
     * @return Generator
     */
    final private static function valueGenerator(...$values)
    {
        foreach ($values as $value){
            if (is_iterable($value)) {
                foreach (static::valueGenerator(...array_values($value)) as $subValue) {
                    yield $subValue;
                }
            } elseif ($value instanceof static) {
                foreach ($value->getValues() as $item) {
                    yield $item;
                }
            } else {
                yield $value;
            }
        }
    }

    /**
     * @param mixed[] $values
     * @return array|mixed[]
     */
    final private static function valueExtractor(...$values): array
    {
        $valuesResult = [];
        foreach (static::valueGenerator(...$values) as $value) {
            $valuesResult[] = $value;
        }
        $valuesResult = array_unique($valuesResult);
        return $valuesResult;
    }

    /**
     * @param mixed[] $names
     * @return Generator
     */
    final private static function nameGenerator(...$names){
        foreach ($names as $name){
            if(is_iterable($name)){
                foreach (static::nameGenerator(...array_values($name)) as $subName){
                    yield $subName;
                }
            } else {
                yield $name;
            }
        }
    }

    /**
     * @param mixed[] $names
     * @return array
     */
    final private static function nameExtractor(...$names)
    {
        $namesResult = [];
        foreach (static::nameGenerator($names) as $name) {
            $namesResult[] = $name;
        }
        $namesResult = array_unique($namesResult);
        return $namesResult;
    }



    /**
     * @param mixed $value
     */
    private function thisAddValue($value):void
    {
        $this->values[static::getName($value)] = $value;
    }

    /**
     * @param string $name
     * @return void
     */
    private function thisAddName(string $name): void
    {
        $this->values[$name] = static::getValue($name);
    }

    /**
     * @param $value
     * @return void
     */
    private function thisRemoveValue($value): void
    {
        unset($this->values[static::getName($value)]);
    }

    /**
     * @param string $name
     * @return void
     */
    private function thisRemoveName(string $name): void
    {
        unset($this->values[$name]);
    }

    /**
     * @param mixed ...$values
     */
    private function thisAddValues(...$values):void
    {
        foreach (static::valueExtractor(...$values) as $value) {
            $this->thisAddValue($value);
        }
    }

    /**
     * @param mixed ...$values
     */
    private function thisSetValues(...$values):void
    {
        $this->values = [];
        $this->thisAddValues(...$values);
    }

    /**
     * @param mixed ...$names
     */
    private function thisAddNames(...$names):void
    {
        foreach (static::nameExtractor(...$names) as $name){
            $this->thisAddName($name);
        }
    }

    /**
     * @param mixed ...$names
     */
    private function thisSetNames(...$names):void
    {
        $this->values = [];
        $this->thisAddNames(...$names);
    }

    /**
     * @param mixed ...$values
     */
    private function thisRemoveValues(...$values):void
    {
        foreach (static::valueGenerator(...$values) as $value){
            $this->thisRemoveValue($value);
        }
    }

    /**
     * @param mixed ...$names
     */
    private function thisRemoveNames(...$names):void
    {
        foreach (static::nameGenerator(...$names) as $name){
            $this->thisRemoveName($name);
        }
    }

    /**
     * @param mixed $values
     * @return static
     */
    public function addValues(...$values): self
    {
        $self = clone $this;
        $self->thisAddValues(...$values);
        return $self;
    }

    /**
     * @param mixed $names
     * @return static
     */
    public function addNames(...$names): self
    {
        $self = clone $this;
        $self->thisAddNames(...$names);
        return $self;
    }

    /**
     * @param mixed $values
     * @return static
     */
    public function setValues(...$values): self
    {
        $self = clone $this;
        $self->thisSetValues(...$values);
        return $self;
    }

    /**
     * @param mixed $names
     * @return static
     */
    public function setNames(...$names): self
    {
        $self = clone $this;
        $self->thisSetNames(...$names);
        return $self;
    }

    /**
     * @param mixed $values
     * @return static
     */
    public function removeValues(...$values): self
    {
        $self = clone $this;
        $self->thisRemoveValues(...$values);
        return $self;
    }

    /**
     * @param mixed $names
     * @return static
     */
    public function removeNames(...$names): self
    {
        $self = clone $this;
        $self->thisRemoveNames(...$names);
        return $self;
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

    /**
     * @param mixed[] ...$values
     * @return bool
     */
    public function in(...$values): bool
    {
        foreach (static::valueGenerator(...$values) as $value) {
            if(in_array($value, $this->values, true)){
                return true;
            }
        }
        return false;
    }

    /**
     * @param mixed[] ...$values
     * @return bool
     */
    public function is(...$values): bool
    {
        foreach (static::valueGenerator(...$values) as $value){
            if(!in_array($value, $this->values, true)){
                return false;
            }
        }
        return true;
    }

    /**
     * @param mixed[] ...$values
     * @return bool
     */
    public function equal(...$values): bool
    {
        $countCheck = 0;
        foreach (static::valueExtractor(...$values) as $value){
            if(!in_array($value, $this->values, true)){
                return false;
            }
            $countCheck++;
        }
        return $countCheck === count($this->values);
    }
}