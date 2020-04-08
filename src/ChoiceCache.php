<?php


namespace Uginroot\PhpSet;

class ChoiceCache
{
    /**
     * @var array|Choice[]
     */
    private $choices = [];

    public function getChoice(string $class):Choice
    {
        if(!array_key_exists($class, $this->choices)){
            $this->choices[$class] = new Choice($class);
        }

        return $this->choices[$class];
    }
}