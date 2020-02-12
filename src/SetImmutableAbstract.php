<?php

namespace Uginroot\PhpSet;

abstract class SetImmutableAbstract implements SetImmutableInterface
{
    use SetTrait;

    /**
     * SetImmutableAbstract constructor.
     * @param mixed ...$values
     */
    public function __construct(...$values)
    {
        static::setObjectValues($this, ...$values);
    }

    /**
     * @param mixed $value
     * @return $this
     */
    final public function addValue($value):self
    {
        $self = clone $this;
        static::addObjectValue($self, $value);
        return $self;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    final public function removeValue($value):self
    {
        $self = clone $this;
        static::removeObjectValue($self, $value);
        return $self;
    }

    /**
     * @param string $name
     * @return $this
     */
    final public function addValueByName(string $name):self
    {
        $self = clone $this;
        static::addObjectValueByName($self, $name);
        return $self;
    }

    /**
     * @param string $name
     * @return $this
     */
    final public function removeValueByName(string $name):self
    {
        $self = clone $this;
        static::removeObjectValueByName($self, $name);
        return $self;
    }

    /**
     * @param mixed ...$values
     * @return $this
     */
    final public function setValues(...$values):self
    {
        $self = clone $this;
        static::setObjectValues($self, ...$values);
        return $self;
    }

    /**
     * @param mixed ...$names
     * @return $this
     */
    final public function setNames(...$names):self
    {
        $self = clone $this;
        static::setObjectNames($self, ...$names);
        return $self;
    }
}