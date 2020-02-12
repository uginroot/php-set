<?php


namespace Uginroot\PhpSet;

abstract class SetAbstract implements SetInterface
{
    use SetTrait;

    /**
     * SetAbstract constructor.
     * @param mixed ...$values
     */
    final public function __construct(...$values)
    {
        $this->setValues(...$values);
    }

    /**
     * @param $value
     * @return $this
     */
    final public function addValue($value): self
    {
        static::addObjectValue($this, $value);
        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    final public function removeValue($value): self
    {
        static::removeObjectValue($this, $value);
        return $this;
    }

    /**
     * @param mixed ...$values
     * @return $this
     */
    final public function setValues(...$values): self
    {
        static::setObjectValues($this, ...$values);
        return $this;
    }

    /**
     * @param mixed ...$names
     * @return $this
     */
    final public function setNames(...$names): self
    {
        static::setObjectNames($this, ...$names);
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    final public function addValueByName(string $name): self
    {
        static::addObjectValueByName($this, $name);
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    final public function removeValueByName(string $name): self
    {
        static::removeObjectValueByName($this, $name);
        return $this;
    }
}