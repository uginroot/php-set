<?php

namespace Uginroot\PhpSet;

interface SetInterface
{
    /**
     * SetInterface constructor.
     * @param mixed ...$values
     */
    public function __construct(...$values);

    /**
     * @return array|string[]
     */
    public static function getNameVariants():array;

    /**
     * @return array|mixed[]
     */
    public static function getValueVariants():array;

    /**
     * Checks if all values are in the object
     *
     * @param mixed ...$values
     * @return bool
     */
    public function is(...$values):bool;

    /**
     * Checks if there is at least one value in the current object
     *
     * @param mixed ...$values
     * @return bool
     */
    public function in(...$values):bool;

    /**
     * Checks if all values match the passed
     *
     * @param mixed ...$values
     * @return bool
     */
    public function equal(...$values):bool;

    /**
     * @param mixed $value
     * @return $this
     */
    public function addValue($value):self;

    /**
     * @param mixed $value
     * @return $this
     */
    public function removeValue($value):self;

    /**
     * @param string $name
     * @return $this
     */
    public function addValueByName(string $name):self;

    /**
     * @param string $name
     * @return $this
     */
    public function removeValueByName(string $name):self;

    /**
     * @return array|mixed[]
     */
    public function getValues():array;

    /**
     * @return array|string[]
     */
    public function getNames():array;

    /**
     * @param mixed ...$values
     * @return $this
     */
    public function setValues(...$values):self;

    /**
     * @param mixed ...$names
     * @return $this
     */
    public function setNames(...$names):self;
}