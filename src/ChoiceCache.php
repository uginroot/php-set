<?php


namespace Uginroot\PhpSet;


use ReflectionException;

class ChoiceCache
{
    /**
     * @var array|Choice[]
     */
    private array $choices = [];

    /**
     * @param string $class
     * @return Choice
     * @throws ReflectionException
     */
    public function getChoice(string $class):Choice
    {
        if(!array_key_exists($class, $this->choices)){
            $this->choices[$class] = new Choice($class);
        }

        return $this->choices[$class];
    }
}