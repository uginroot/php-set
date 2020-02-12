<?php
/** @noinspection PhpUnused */

namespace Uginroot\PhpSet\Benchmark;

use PhpBench\Benchmark\Metadata\Annotations\BeforeMethods;
use ReflectionClass;
use ReflectionException;
use Uginroot\PhpSet\Benchmark\Classes\AnimalImmutable as Animal;
use Uginroot\PhpSet\SetImmutableAbstract as SetAbstract;

/**
 * @BeforeMethods("init")
 */
class SetImmutableBench
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
            Animal::getName($value);
        }
    }

    public function benchGetNameValue()
    {
        foreach ($this->animalNames as $name) {
            Animal::getValue($name);
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

    public function benchAddValues()
    {
        $this->animalEmpty->addValues($this->animalValues);
        $this->animalEmpty->removeValues($this->animalValues);
    }

    public function benchAddNames()
    {
        $this->animalEmpty->addNames($this->animalNames);
        $this->animalEmpty->removeNames($this->animalNames);
    }

    public function benchRemoveValues()
    {
        $this->animalFull->removeValues($this->animalValues);
        $this->animalFull->addValues($this->animalValues);
    }

    public function benchRemoveNames()
    {
        $this->animalFull->removeNames($this->animalNames);
        $this->animalFull->addNames($this->animalNames);
    }

    public function benchSetValues()
    {
        $this->animalEmpty->setValues($this->animalNames);
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