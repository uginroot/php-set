<?php

namespace Uginroot\PhpSet;

interface SetInterface
{
    /**
     * @return array|string[]
     */
    public static function getNameVariants():array;

    /**
     * @return array|mixed[]
     */
    public static function getValueVariants():array;

    /**
     * @param mixed ...$values
     * @return bool
     */
    public function is(...$values):bool;

    /**
     * @param mixed ...$values
     * @return bool
     */
    public function in(...$values):bool;

    /**
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
}