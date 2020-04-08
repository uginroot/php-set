<?php


namespace Uginroot\PhpSet;

use Generator;
use ReflectionClass;
use Uginroot\PhpSet\Exception\IncorrectNameException;
use Uginroot\PhpSet\Exception\IncorrectValueException;
use Uginroot\PhpSet\Exception\DuplicateValueException;

class Choice
{
    /** @var string */
    private $class;

    /** @var array|mixed[] */
    private $values = [];

    /** @var array|string[] */
    private $names = [];


    public function __construct(string $class)
    {
        $this->class = $class;
        $reflectionClass = new ReflectionClass($class);

        foreach ($reflectionClass->getReflectionConstants() as $reflectionConstant){
            if(!$reflectionConstant->isPublic()){
                continue;
            }

            $this->values[] = $reflectionConstant->getValue();
            $this->names[] = $reflectionConstant->getName();
        }

        if(count(array_unique($this->values)) !== count($this->values)){
            throw new DuplicateValueException("Duplicate public constant value in class {$class}");
        }
    }

    public function getValues():array
    {
        return $this->values;
    }

    /**
     * @return array|string[]
     */
    public function getNames():array
    {
        return $this->names;
    }

    private function getValueIndex($value):int
    {
        $index = array_search($value, $this->values, true);

        if($index === false){
            throw new IncorrectValueException("Incorrect public constant value '{$value}' in class {$this->class} ");
        }

        return $index;
    }

    private function getNameIndex(string $name):int
    {
        $index = array_search($name, $this->names, true);

        if($index === false){
            throw new IncorrectNameException("Incorrect constant '{$name}' in class {$this->class} ");
        }

        return $index;
    }

    public function getName($value):string
    {
        return $this->names[$this->getValueIndex($value)];
    }

    public function getValue(string $name)
    {
        return $this->values[$this->getNameIndex($name)];
    }

    /**
     * @param array $names
     * @return Generator|string[]
     */
    private function generatorNames(...$names):Generator
    {
        foreach ($names as $name){
            if(is_iterable($name)){
                foreach (static::generatorNames(...array_values($name)) as $subName){
                    yield $subName;
                }
            } elseif ($name instanceof SetAbstract) {
                foreach ($name->getNames() as $item) {
                    yield $item;
                }
            } else {
                yield $name;
            }
        }
    }

    /**
     * @param mixed ...$names
     * @return array|string[]
     */
    public function extractorNames(...$names):array
    {
        $namesUnique = array_unique(iterator_to_array($this->generatorNames(...$names)));
        foreach ($namesUnique as $name){
            $this->getNameIndex($name);
        }
        return $namesUnique;
    }

    private function generatorValues(...$values):Generator
    {
        foreach ($values as $value){
            if (is_iterable($value)) {
                foreach (static::generatorValues(...array_values($value)) as $subValue) {
                    yield $subValue;
                }
            } elseif ($value instanceof SetAbstract) {
                foreach ($value->getValues() as $item) {
                    yield $item;
                }
            } else {
                yield $value;
            }
        }
    }

    public function extractorValues(...$values):array
    {
        $valuesUnique = array_unique(iterator_to_array($this->generatorValues(...$values)));
        foreach ($valuesUnique as $value){
            $this->getValueIndex($value);
        }
        return $valuesUnique;
    }

    /**
     * @param mixed ...$values
     * @return array|string[];
     */
    public function findNames(...$values):array
    {
        $valuesUnique = array_unique(iterator_to_array($this->generatorValues(...$values)));
        $names = [];
        foreach ($valuesUnique as $value){
            $names[] = $this->getName($value);
        }
        return $names;
    }

    public function findValues(...$names):array
    {
        $namesUnique = array_unique(iterator_to_array($this->generatorNames(...$names)));
        $values = [];
        foreach ($namesUnique as $name){
            $values[] = $this->getValue($name);
        }
        return $values;
    }
}