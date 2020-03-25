<?php


namespace Uginroot\PhpSet;

use ReflectionException;

class SetAbstract
{
    /** @var ChoiceCache|null */
    private static $choiceCache;

    /** @var array|mixed[] */
    private $values;

    /** @var array|string[] */
    private $names;

    /**
     * @return Choice
     * @throws ReflectionException
     */
    public static function getChoice():Choice
    {
        if(self::$choiceCache === null){
            self::$choiceCache = new ChoiceCache();
        }

        return self::$choiceCache->getChoice(static::class);
    }

    /**
     * @param array|string[] ...$names
     * @return static
     * @throws ReflectionException
     */
    public static function createFromNames(...$names): self
    {
        return new static(static::getChoice()->findValues($names));
    }

    /**
     * @param array|mixed[] $values
     * @return static
     * @throws ReflectionException
     */
    public static function createFromValues(...$values): self
    {
        return new static($values);
    }

    /**
     * SetAbstract constructor.
     * @param mixed ...$values
     * @throws ReflectionException
     */
    public function __construct(...$values)
    {
        $this->values = static::getChoice()->extractorValues($values);
        $this->names = static::getChoice()->findNames($this->values);
    }

    /**
     * @param mixed $values
     * @return static
     * @throws ReflectionException
     */
    public function addValues(...$values): self
    {
        return new static($values, $this->values);
    }

    /**
     * @param mixed $names
     * @return static
     * @throws ReflectionException
     */
    public function addNames(...$names): self
    {
        return new static(static::getChoice()->findValues($names), $this->values);
    }

    /**
     * @param mixed $values
     * @return static
     * @throws ReflectionException
     */
    public function setValues(...$values): self
    {
        return new static(...$values);
    }

    /**
     * @param mixed $names
     * @return static
     * @throws ReflectionException
     */
    public function setNames(...$names): self
    {
        return new static(...static::getChoice()->findValues($names));
    }

    /**
     * @param mixed $values
     * @return static
     * @throws ReflectionException
     */
    public function removeValues(...$values): self
    {
        return new static(array_diff($this->values, static::getChoice()->extractorValues($values)));
    }

    /**
     * @param mixed $names
     * @return static
     * @throws ReflectionException
     */
    public function removeNames(...$names): self
    {
        return new static(array_diff($this->values, static::getChoice()->findValues($names)));
    }

    /**
     * @return array|string[]
     */
    public function getNames(): array
    {
        return $this->names;
    }

    /**
     * @return array|mixed[]
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param mixed[] ...$values
     * @return bool
     * @throws ReflectionException
     */
    public function in(...$values): bool
    {
        foreach (static::getChoice()->extractorValues($values) as $value) {
            if(in_array($value, $this->values, true)){
                return true;
            }
        }
        return false;
    }

    /**
     * @param mixed[] ...$values
     * @return bool
     * @throws ReflectionException
     */
    public function is(...$values): bool
    {
        foreach (static::getChoice()->extractorValues($values) as $value){
            if(!in_array($value, $this->values, true)){
                return false;
            }
        }
        return true;
    }

    /**
     * @param mixed[] ...$values
     * @return bool
     * @throws ReflectionException
     */
    public function equal(...$values): bool
    {
        $countCheck = 0;
        foreach (static::getChoice()->extractorValues($values) as $value){
            if(!in_array($value, $this->values, true)){
                return false;
            }
            $countCheck++;
        }
        return $countCheck === count($this->values);
    }
}