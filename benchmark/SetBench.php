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
    public function init():void
    {
        $this->animalFull       = new Animal(Animal::Wolf, Animal::Dog, Animal::Cat, Animal::Lion);
        $this->animalEmpty      = new Animal();
        $this->reflectionAnimal = new ReflectionClass($this->animalFull);
        $this->animalNames      = Animal::getChoice()->getNames();
        $this->animalValues     = Animal::getChoice()->getValues();
    }

    /**
     * @throws ReflectionException
     */
    public function benchGetNameVariants():void
    {
        Animal::getChoice()->getNames();
    }

    /**
     * @throws ReflectionException
     */
    public function benchGetValueVariants():void
    {
        Animal::getChoice()->getValues();
    }

    /**
     * @throws ReflectionException
     */
    public function benchGetValueName():void
    {
        $choice = Animal::getChoice();
        foreach ($this->animalValues as $value) {
            $choice->getName($value);
        }
    }

    /**
     * @throws ReflectionException
     */
    public function benchGetNameValue():void
    {
        $choice = Animal::getChoice();
        foreach ($this->animalNames as $name) {
            $choice->getValue($name);
        }
    }

    public function benchConstructor():void
    {
        new Animal(Animal::Wolf, Animal::Dog, Animal::Cat, Animal::Lion);
    }

    public function benchConstructorWithoutValues():void
    {
        new Animal;
    }

    /**
     * @throws ReflectionException
     */
    public function benchConstructorWithObject():void
    {
        new Animal($this->animalFull);
    }

    /**
     * @throws ReflectionException
     */
    public function benchAddValue():void
    {
        $this->animalEmpty->addValues($this->animalValues);
    }

    /**
     * @throws ReflectionException
     */
    public function benchAddValueByName():void
    {
        $this->animalEmpty->addNames($this->animalNames);
    }

    /**
     * @throws ReflectionException
     */
    public function benchRemoveValue():void
    {
        $this->animalFull->removeValues($this->animalValues);

    }

    /**
     * @throws ReflectionException
     */
    public function benchRemoveValueByName():void
    {
        $this->animalFull->removeNames($this->animalNames);
    }

    /**
     * @throws ReflectionException
     */
    public function benchSetValues():void
    {
        $this->animalEmpty->setValues(Animal::Lion, Animal::Cat, Animal::Dog, Animal::Wolf);
    }

    public function benchGetNames():void
    {
        $this->animalFull->getNames();
    }

    public function benchGetValues():void
    {
        $this->animalFull->getValues();
    }

    /**
     * @throws ReflectionException
     * @throws ReflectionException
     */
    public function benchIn():void
    {
        $this->animalFull->in(Animal::Dog, Animal::Cat);
        $this->animalEmpty->in(Animal::Dog, Animal::Cat);
    }

    /**
     * @throws ReflectionException
     */
    public function benchIs():void
    {
        $this->animalEmpty->is(Animal::Dog, Animal::Cat);
        $this->animalEmpty->is(Animal::Dog, Animal::Cat);
    }

    /**
     * @throws ReflectionException
     */
    public function benchEqual():void
    {
        $this->animalFull->is(Animal::Dog, Animal::Cat, Animal::Wolf, Animal::Lion);
        $this->animalFull->is(Animal::Dog, Animal::Cat);
    }

    /**
     * @throws ReflectionException
     */
    public function benchSetNames():void
    {
        $this->animalFull->setNames(...$this->animalNames);
    }

    /**
     * @throws ReflectionException
     */
    public function benchCreateFromNames():void
    {
        Animal::createFromNames('Cat', 'Dog');
    }
}