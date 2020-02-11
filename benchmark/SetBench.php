<?php
/** @noinspection PhpUnused */

namespace Uginroot\PhpSet\Benchmark;

use PhpBench\Benchmark\Metadata\Annotations\BeforeMethods;
use ReflectionClass;
use ReflectionException;
use Uginroot\PhpSet\Benchmark\Classes\Animal;
use Uginroot\PhpSet\SetAbstract;

/**
 * @BeforeMethods("init")
 */
class SetBench
{
    private ?SetAbstract $animalFull;
    private ?ReflectionClass $reflectionAnimal;
    private ?array $animalNames;
    private ?array $animalValues;
    private ?Animal $animalEmpty;

    /**
     * @throws ReflectionException
     */
    public function init()
    {
        $this->animalFull       = new Animal(Animal::Wolf, Animal::Dog, Animal::Cat, Animal::Lion);
        $this->animalEmpty      = new Animal();
        $this->reflectionAnimal = new ReflectionClass($this->animalFull);
        $this->animalNames      = $this->animalFull::getNameVariants();
        $this->animalValues     = $this->animalFull::getValueVariants();
    }

    public function benchGetNameVariants()
    {
        Animal::getNameVariants();
    }

    public function benchGetValueVariants()
    {
        Animal::getValueVariants();
    }

    public function benchGetValueName()
    {
        foreach ($this->animalValues as $value) {
            Animal::getValueName($value);
        }
    }

    public function benchGetNameValue()
    {
        foreach ($this->animalNames as $name) {
            Animal::getNameValue($name);
        }
    }

    public function benchConstructor()
    {
        new Animal(Animal::Wolf, Animal::Dog, Animal::Cat, Animal::Lion);
    }

    public function benchConstructorWithoutValues()
    {
        new Animal;
    }

    public function benchConstructorWithObject()
    {
        new Animal($this->animalFull);
    }

    public function benchAddValue()
    {
        foreach ($this->animalValues as $value) {
            $this->animalEmpty->addValue($value);
        }
    }

    public function benchAddValueByName()
    {
        foreach ($this->animalNames as $name) {
            $this->animalEmpty->addValueByName($name);
        }
    }

    public function benchRemoveValue()
    {
        foreach ($this->animalValues as $value) {
            $this->animalFull->removeValue($value);
        }
    }

    public function benchRemoveValueByName()
    {
        foreach ($this->animalNames as $name) {
            $this->animalFull->addValueByName($name);
        }
    }

    public function benchSetValues()
    {
        $this->animalEmpty->setValues(Animal::Lion, Animal::Cat, Animal::Dog, Animal::Wolf);
    }

    public function benchGetNames()
    {
        $this->animalFull->getNames();
    }

    public function benchGetValues()
    {
        $this->animalFull->getValues();
    }

    public function benchIn()
    {
        $this->animalFull->in(Animal::Dog, Animal::Cat);
        $this->animalEmpty->in(Animal::Dog, Animal::Cat);
    }

    public function benchIs()
    {
        $this->animalEmpty->is(Animal::Dog, Animal::Cat);
        $this->animalEmpty->is(Animal::Dog, Animal::Cat);
    }
    
    public function benchEqual()
    {
        $this->animalFull->is(Animal::Dog, Animal::Cat, Animal::Wolf, Animal::Lion);
        $this->animalFull->is(Animal::Dog, Animal::Cat);
    }

    public function benchSetNames(){
        $this->animalFull->setNames(...$this->animalNames);
    }
}