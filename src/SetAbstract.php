<?php


namespace Uginroot\PhpSet;

class SetAbstract
{
    /** @var ChoiceCache|null */
    private static $choiceCache;

    /** @var array|mixed[] */
    private $values;

    /** @var array|string[] */
    private $names;


    public static function equals(?SetAbstract $a, ?SetAbstract $b): bool
    {
        if($a === $b){
            return true;
        }

        if($a === null || $b === null){
            return false;
        }

        if(get_class($a) !== get_class($b)){
            return false;
        }

        return $a->equal($b);
    }

    public static function getChoice():Choice
    {
        if(self::$choiceCache === null){
            self::$choiceCache = new ChoiceCache();
        }

        return self::$choiceCache->getChoice(static::class);
    }

    public static function createFromNames(...$names): self
    {
        return new static(static::getChoice()->findValues($names));
    }

    public static function createFromValues(...$values): self
    {
        return new static($values);
    }

    public function __construct(...$values)
    {
        $this->values = static::getChoice()->extractorValues($values);
        $this->names = static::getChoice()->findNames($this->values);
    }

    public function addValues(...$values): self
    {
        return new static($values, $this->values);
    }

    public function addNames(...$names): self
    {
        return new static(static::getChoice()->findValues($names), $this->values);
    }

    public function setValues(...$values): self
    {
        return new static(...$values);
    }

    public function setNames(...$names): self
    {
        return new static(...static::getChoice()->findValues($names));
    }

    public function removeValues(...$values): self
    {
        return new static(array_diff($this->values, static::getChoice()->extractorValues($values)));
    }

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

    public function getValues(): array
    {
        return $this->values;
    }

    public function in(...$values): bool
    {
        foreach (static::getChoice()->extractorValues($values) as $value) {
            if(in_array($value, $this->values, true)){
                return true;
            }
        }
        return false;
    }

    public function is(...$values): bool
    {
        foreach (static::getChoice()->extractorValues($values) as $value){
            if(!in_array($value, $this->values, true)){
                return false;
            }
        }
        return true;
    }

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