<?php

namespace Uginroot\PhpSet;

use Uginroot\PhpSet\Traits\SetTrait;

abstract class SetImmutableAbstract implements SetImmutableInterface
{
    use SetTrait;

    /**
     * SetAbstract constructor.
     * @param mixed ...$values
     */
    final public function __construct(...$values)
    {
        static::addObjectValues($this, $values);
    }

    /**
     * @param mixed ...$values
     * @return $this
     */
    final public function setValues(...$values):self
    {
        $self = clone $this;
        static::setObjectValues($self, $values);
        return $self;
    }

    /**
     * @param string ...$names
     * @return $this
     */
    final public function setNames(...$names):self
    {
        $self = clone $this;
        static::setObjectNames($self, $names);
        return $self;
    }

    /**
     * @param mixed ...$values
     * @return SetInterface
     */
    public function addValues(...$values):SetInterface
    {
        $self = clone $this;
        static::addObjectValues($self, $values);
        return $self;
    }

    /**
     * @param string ...$names
     * @return SetInterface
     */
    public function addNames(...$names):SetInterface
    {
        $self = clone $this;
        static::addObjectNames($self, $names);
        return $self;
    }

    /**
     * @param mixed ...$values
     * @return SetInterface
     */
    public function removeValues(...$values):SetInterface
    {
        $self = clone $this;
        static::removeObjectValues($self, $values);
        return $self;
    }

    /**
     * @param string ...$names
     * @return SetInterface
     */
    public function removeNames(...$names):SetInterface
    {
        $self = clone $this;
        static::removeObjectNames($self, $names);
        return $self;
    }
}