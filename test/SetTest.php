<?php


namespace Uginroot\PhpSet\Test;


use PHPUnit\Framework\TestCase;
use Uginroot\PhpSet\Test\Classes\Align;
use Uginroot\PhpSet\Test\Classes\Animal;


class SetTest extends TestCase
{
    public function testGetNameVariants()
    {
        $this->assertEqualsCanonicalizing(['Left', 'Center', 'Right'], Align::getNameVariants());
    }

    public function testGetValueVariants()
    {
        $this->assertEqualsCanonicalizing([Align::Left, Align::Right, Align::Center], Align::getValueVariants());
    }

    public function testGetValueName()
    {
        $this->assertSame('Center', Align::getValueName(Align::Center));
    }

    public function testGetNameValue()
    {
        $this->assertSame('center', Align::getNameValue('Center'));
    }

    public function testGetNames()
    {
        $align = new Align(Align::Left, Align::Right, Align::Center);
        $this->assertEqualsCanonicalizing(['Left', 'Right', 'Center'], $align->getNames());
    }

    public function testGetValues()
    {
        $align = new Align(Align::Left, Align::Right, Align::Center);
        $this->assertEqualsCanonicalizing(['Left', 'Right', 'Center'], $align->getNames());
    }

    public function testConstructor()
    {
        $align = new Align(Align::Left, Align::Right, Align::Center);

        $this->assertEqualsCanonicalizing([Align::Left, Align::Right, Align::Center], $align->getValues());
        $this->assertEqualsCanonicalizing(['Left', 'Right', 'Center'], $align->getNames());
    }

    public function testConstructorWithoutValues()
    {
        $align = new Align;

        $this->assertEqualsCanonicalizing([], $align->getValues());
        $this->assertEqualsCanonicalizing([], $align->getNames());
    }

    public function testConstructorWithObject()
    {
        $left = new Align(Align::Left);
        $align = new Align($left, Align::Right);

        $this->assertEqualsCanonicalizing([Align::Right, Align::Left], $align->getValues());
        $this->assertEqualsCanonicalizing(['Right', 'Left'], $align->getNames());
    }

    public function testAddValue()
    {
        $align = new Align;
        $align->addValue(Align::Center);

        $this->assertEqualsCanonicalizing([Align::Center], $align->getValues());
        $this->assertEqualsCanonicalizing(['Center'], $align->getNames());
    }

    public function testRemoveValue()
    {
        $align = new Align(Align::Left, Align::Center);
        $align->removeValue(Align::Center);

        $this->assertEqualsCanonicalizing([Align::Left], $align->getValues());
        $this->assertEqualsCanonicalizing(['Left'], $align->getNames());
    }

    public function testSetValues()
    {
        $align = new Align;
        $align->setValues(Align::Left, Align::Right, Align::Center);

        $this->assertEqualsCanonicalizing([Align::Left, Align::Right, Align::Center], $align->getValues());
        $this->assertEqualsCanonicalizing(['Left', 'Right', 'Center'], $align->getNames());
    }

    public function testAddValueByName()
    {
        $align = new Align;
        $align->addValueByName('Center');

        $this->assertEqualsCanonicalizing([Align::Center], $align->getValues());
        $this->assertEqualsCanonicalizing(['Center'], $align->getNames());
    }

    public function testRemoveValueByName()
    {
        $align = new Align(Align::Left, Align::Center);
        $align->removeValueByName('Center');

        $this->assertEqualsCanonicalizing([Align::Left], $align->getValues());
        $this->assertEqualsCanonicalizing(['Left'], $align->getNames());
    }

    public function testIn()
    {
        $animalsAll = new Animal(Animal::getValueVariants());

        $animalsPets = new Animal(Animal::Dog, Animal::Cat);
        $animalsWild = new Animal(Animal::Wolf, Animal::Lion);

        $animalsCat = new Animal(Animal::Cat, Animal::Lion);
        $animalsDog = new Animal(Animal::Dog, Animal::Wolf);

        // All animals
        $this->assertTrue($animalsAll->in(Animal::Dog));
        $this->assertTrue($animalsAll->in(Animal::Cat));
        $this->assertTrue($animalsAll->in(Animal::Wolf));
        $this->assertTrue($animalsAll->in(Animal::Lion));

        $this->assertTrue($animalsAll->in($animalsPets));
        $this->assertTrue($animalsAll->in($animalsWild));
        $this->assertTrue($animalsAll->in($animalsCat));
        $this->assertTrue($animalsAll->in($animalsDog));

        // Animals pets
        $this->assertTrue($animalsPets->in(Animal::Dog));
        $this->assertTrue($animalsPets->in(Animal::Cat));
        $this->assertFalse($animalsPets->in(Animal::Wolf));
        $this->assertFalse($animalsPets->in(Animal::Lion));

        $this->assertTrue($animalsPets->in($animalsCat));
        $this->assertTrue($animalsPets->in($animalsDog));
        $this->assertFalse($animalsPets->in($animalsWild));
        $this->assertTrue($animalsPets->in($animalsPets));

        // Animals wild
        $this->assertFalse($animalsWild->in(Animal::Dog));
        $this->assertFalse($animalsWild->in(Animal::Cat));
        $this->assertTrue($animalsWild->in(Animal::Wolf));
        $this->assertTrue($animalsWild->in(Animal::Lion));

        $this->assertTrue($animalsWild->in($animalsCat));
        $this->assertTrue($animalsWild->in($animalsDog));
        $this->assertTrue($animalsWild->in($animalsWild));
        $this->assertFalse($animalsWild->in($animalsPets));

        // Animals cat
        $this->assertFalse($animalsCat->in(Animal::Dog));
        $this->assertTrue($animalsCat->in(Animal::Cat));
        $this->assertFalse($animalsCat->in(Animal::Wolf));
        $this->assertTrue($animalsCat->in(Animal::Lion));

        $this->assertTrue($animalsCat->in($animalsCat));
        $this->assertFalse($animalsCat->in($animalsDog));
        $this->assertTrue($animalsCat->in($animalsWild));
        $this->assertTrue($animalsCat->in($animalsPets));

        // Animals dog
        $this->assertTrue($animalsDog->in(Animal::Dog));
        $this->assertFalse($animalsDog->in(Animal::Cat));
        $this->assertTrue($animalsDog->in(Animal::Wolf));
        $this->assertFalse($animalsDog->in(Animal::Lion));

        $this->assertFalse($animalsDog->in($animalsCat));
        $this->assertTrue($animalsDog->in($animalsDog));
        $this->assertTrue($animalsDog->in($animalsWild));
        $this->assertTrue($animalsDog->in($animalsPets));
    }

    public function testIs()
    {
        $animalsAll = new Animal(Animal::getValueVariants());

        $animalsPets = new Animal(Animal::Dog, Animal::Cat);
        $animalsWild = new Animal(Animal::Wolf, Animal::Lion);

        $animalsCat = new Animal(Animal::Cat, Animal::Lion);
        $animalsDog = new Animal(Animal::Dog, Animal::Wolf);

        // All animals
        $this->assertTrue($animalsAll->is(Animal::Dog));
        $this->assertTrue($animalsAll->is(Animal::Cat));
        $this->assertTrue($animalsAll->is(Animal::Wolf));
        $this->assertTrue($animalsAll->is(Animal::Lion));

        $this->assertTrue($animalsAll->is($animalsPets));
        $this->assertTrue($animalsAll->is($animalsWild));
        $this->assertTrue($animalsAll->is($animalsCat));
        $this->assertTrue($animalsAll->is($animalsDog));

        // Animals pets
        $this->assertTrue($animalsPets->is(Animal::Dog));
        $this->assertTrue($animalsPets->is(Animal::Cat));
        $this->assertFalse($animalsPets->is(Animal::Wolf));
        $this->assertFalse($animalsPets->is(Animal::Lion));

        $this->assertFalse($animalsPets->is($animalsCat));
        $this->assertFalse($animalsPets->is($animalsDog));
        $this->assertFalse($animalsPets->is($animalsWild));
        $this->assertTrue($animalsPets->is($animalsPets));

        // Animals wild
        $this->assertFalse($animalsWild->is(Animal::Dog));
        $this->assertFalse($animalsWild->is(Animal::Cat));
        $this->assertTrue($animalsWild->is(Animal::Wolf));
        $this->assertTrue($animalsWild->is(Animal::Lion));

        $this->assertFalse($animalsWild->is($animalsCat));
        $this->assertFalse($animalsWild->is($animalsDog));
        $this->assertTrue($animalsWild->is($animalsWild));
        $this->assertFalse($animalsWild->is($animalsPets));

        // Animals cat
        $this->assertFalse($animalsCat->is(Animal::Dog));
        $this->assertTrue($animalsCat->is(Animal::Cat));
        $this->assertFalse($animalsCat->is(Animal::Wolf));
        $this->assertTrue($animalsCat->is(Animal::Lion));

        $this->assertTrue($animalsCat->is($animalsCat));
        $this->assertFalse($animalsCat->is($animalsDog));
        $this->assertFalse($animalsCat->is($animalsWild));
        $this->assertFalse($animalsCat->is($animalsPets));

        // Animals dog
        $this->assertTrue($animalsDog->is(Animal::Dog));
        $this->assertFalse($animalsDog->is(Animal::Cat));
        $this->assertTrue($animalsDog->is(Animal::Wolf));
        $this->assertFalse($animalsDog->is(Animal::Lion));

        $this->assertFalse($animalsDog->is($animalsCat));
        $this->assertTrue($animalsDog->is($animalsDog));
        $this->assertFalse($animalsDog->is($animalsWild));
        $this->assertFalse($animalsDog->is($animalsPets));
    }

    public function testEqual()
    {
        $animalsPets = new Animal(Animal::Dog, Animal::Cat);
        $animalsWild = new Animal(Animal::Wolf, Animal::Lion);
        $animalsCat = new Animal(Animal::Cat, Animal::Lion);
        $animalsDog = new Animal(Animal::Dog, Animal::Wolf);

        $this->assertTrue($animalsPets->equal(Animal::Dog, Animal::Cat));
        $this->assertTrue($animalsPets->equal(Animal::Cat, Animal::Dog));
        $this->assertTrue($animalsPets->equal(new Animal(Animal::Dog, Animal::Cat)));

        $this->assertFalse($animalsPets->equal(Animal::Dog, Animal::Dog));
        $this->assertFalse($animalsPets->equal(Animal::Dog, Animal::Dog, Animal::Lion));
        $this->assertFalse($animalsPets->equal($animalsWild));
        $this->assertFalse($animalsPets->equal($animalsCat));
        $this->assertFalse($animalsPets->equal($animalsDog));
    }

    public function testSetNames()
    {
        $animalsPets = new Animal(Animal::Lion);
        $animalsPets->setNames('Cat', 'Dog');
        $this->assertEqualsCanonicalizing([Animal::Cat, Animal::Dog], $animalsPets->getValues());
    }
}