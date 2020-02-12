<?php


namespace Uginroot\PhpSet;

use Uginroot\PhpSet\Traits\SetTrait;

abstract class SetAbstract implements SetInterface
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
    final public function setValues(...$values): self
    {
        static::setObjectValues($this, $values);
        return $this;
    }

    /**
     * @param string ...$names
     * @return $this
     */
    final public function setNames(...$names): self
    {
        static::setObjectNames($this, $names);
        return $this;
    }

    /**
     * @param mixed ...$values
     * @return $this
     */
    public function addValues(...$values): self
    {
        static::addObjectValues($this, $values);
        return $this;
    }

    /**
     * @param string ...$names
     * @return $this
     */
    public function addNames(...$names): self
    {
        static::addObjectNames($this, $names);
        return $this;
    }

    /**
     * @param mixed ...$values
     * @return $this
     */
    public function removeValues(...$values): self
    {
        static::removeObjectValues($this, $values);
        return $this;
    }

    /**
     * @param string ...$names
     * @return $this
     */
    public function removeNames(...$names): self
    {
        static::removeObjectNames($this, $names);
        return $this;
    }


}