<?php

namespace Uginroot\PhpSet\Traits;

use ReflectionClass;
use Uginroot\PhpSet\Exception\DuplicateValueException;
use Uginroot\PhpSet\Exception\IncorrectNameException;
use Uginroot\PhpSet\Exception\IncorrectValueException;

trait InitTrait
{
    /**
     * @var mixed[] Constants cache list by all children class
     */
    private static array $valueCache = [];

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
    final public static function getName($value): string
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
    final public static function getValue(string $name)
    {
        static::init();
        if (!key_exists($name, static::$valueCache[static::class])) {
            throw new IncorrectNameException("Constant '{$name}' not in class " . static::class);
        }
        return static::$valueCache[static::class][$name];
    }
}