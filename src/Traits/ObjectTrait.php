<?php


namespace Uginroot\PhpSet\Traits;


use Uginroot\PhpSet\SetAbstract;

trait ObjectTrait
{
    use GeneratorTrait;

    /**
     * @var mixed[] Values current Set object
     */
    private array $values = [];

    /**
     * @param mixed ...$names
     * @return $this
     */
    public static function createFromNames(...$names): self
    {
        $self = new static();
        /** @noinspection PhpParamsInspection */
        static::addObjectNames($self, $names);
        return $self;
    }

    /**
     * SetAbstract constructor.
     * @param mixed ...$values
     */
    final public function __construct(...$values)
    {
        /** @noinspection PhpParamsInspection */
        static::addObjectValues($this, $values);
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
}