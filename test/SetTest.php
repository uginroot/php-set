<?php


namespace Uginroot\PhpSet\Test;


use PHPUnit\Framework\TestCase;
use Uginroot\PhpSet\Test\Classes\Align;
use Uginroot\PhpSet\Test\Classes\Animal;


class SetTest extends TestCase
{
    public function testChoiceExtractorNames():void
    {
        $names = Align::getChoice()->extractorNames(
            'Left',
            [
                'Center',
                'Center',
                [
                    new Align(Align::Right)
                ]
            ]
        );
        sort($names);
        $this->assertSame(['Center', 'Left', 'Right'], $names);
    }

    public function testChoiceExtractorValues():void
    {
        $values = Align::getChoice()->extractorValues(
            Align::Left,
            [
                Align::Center,
                Align::Center,
                [
                    new Align(Align::Right)
                ]
            ]
        );
        sort($values);
        $this->assertSame(['center', 'left', 'right'], $values);
    }

    public function testGetNameVariants():void
    {
        $this->assertEqualsCanonicalizing(['Left', 'Center', 'Right'], Align::getChoice()->getNames());
    }

    public function testGetValueVariants():void
    {
        $this->assertEqualsCanonicalizing([Align::Left, Align::Right, Align::Center], Align::getChoice()->getValues());
    }

    public function testGetValueName():void
    {
        $this->assertSame('Center', Align::getChoice()->getName(Align::Center));
    }

    public function testGetNameValue():void
    {
        $this->assertSame('center', Align::getChoice()->getValue('Center'));
    }

    public function testGetNames():void
    {
        $align = new Align(Align::Left, Align::Right, Align::Center);
        $this->assertEqualsCanonicalizing(['Left', 'Right', 'Center'], $align->getNames());
    }

    public function testGetValues():void
    {
        $align = new Align(Align::Left, Align::Right, Align::Center);
        $this->assertEqualsCanonicalizing(['Left', 'Right', 'Center'], $align->getNames());
    }

    public function testConstructor():void
    {
        $align = new Align(Align::Left, Align::Right, Align::Center);

        $this->assertEqualsCanonicalizing([Align::Left, Align::Right, Align::Center], $align->getValues());
        $this->assertEqualsCanonicalizing(['Left', 'Right', 'Center'], $align->getNames());
    }

    public function testConstructorWithoutValues():void
    {
        $align = new Align;

        $this->assertEqualsCanonicalizing([], $align->getValues());
        $this->assertEqualsCanonicalizing([], $align->getNames());
    }

    public function testConstructorWithObject():void
    {
        $left = new Align(Align::Left);
        $align = new Align($left, Align::Right);

        $this->assertEqualsCanonicalizing([Align::Right, Align::Left], $align->getValues());
        $this->assertEqualsCanonicalizing(['Right', 'Left'], $align->getNames());
    }

    public function testAddValues():void
    {
        $align = new Align;
        $newAlign = $align->addValues(Align::Center);

        $this->assertEqualsCanonicalizing([Align::Center], $newAlign->getValues());
        $this->assertEqualsCanonicalizing(['Center'], $newAlign->getNames());

        $this->assertEqualsCanonicalizing([], $align->getValues());
        $this->assertEqualsCanonicalizing([], $align->getNames());
    }

    public function testRemoveValues():void
    {
        $align = new Align(Align::Left, Align::Center);
        $newAlign = $align->removeValues(Align::Center);

        $this->assertEqualsCanonicalizing([Align::Left], $newAlign->getValues());
        $this->assertEqualsCanonicalizing(['Left'], $newAlign->getNames());

        $this->assertEqualsCanonicalizing([Align::Left, Align::Center], $align->getValues());
        $this->assertEqualsCanonicalizing(['Left', 'Center'], $align->getNames());
    }

    public function testSetValues():void
    {
        $align = new Align;
        $newAlign = $align->setValues(Align::Left, Align::Right, Align::Center);

        $this->assertEqualsCanonicalizing([Align::Left, Align::Right, Align::Center], $newAlign->getValues());
        $this->assertEqualsCanonicalizing(['Left', 'Right', 'Center'], $newAlign->getNames());

        $this->assertEqualsCanonicalizing([], $align->getValues());
        $this->assertEqualsCanonicalizing([], $align->getNames());
    }

    public function testAddNames():void
    {
        $align = new Align;
        $newAlign = $align->addNames('Center');

        $this->assertEqualsCanonicalizing([Align::Center], $newAlign->getValues());
        $this->assertEqualsCanonicalizing(['Center'], $newAlign->getNames());

        $this->assertEqualsCanonicalizing([], $align->getValues());
        $this->assertEqualsCanonicalizing([], $align->getNames());
    }

    public function testRemoveNames():void
    {
        $align = new Align(Align::Left, Align::Center);
        $newAlign = $align->removeNames('Center');

        $this->assertEqualsCanonicalizing([Align::Left], $newAlign->getValues());
        $this->assertEqualsCanonicalizing(['Left'], $newAlign->getNames());

        $this->assertEqualsCanonicalizing([Align::Left, Align::Center], $align->getValues());
        $this->assertEqualsCanonicalizing(['Left', 'Center'], $align->getNames());
    }

    public function testIn():void
    {
        $animalsAll = new Animal(Animal::getChoice()->getValues());

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

    public function testIs():void
    {
        $animalsAll = new Animal(Animal::getChoice()->getValues());

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

    public function testEqual():void
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

    public function testSetNames():void
    {
        $animalsPets = new Animal(Animal::Lion);
        $newAnimalsPets = $animalsPets->setNames('Cat', 'Dog');

        $this->assertEqualsCanonicalizing([Animal::Cat, Animal::Dog], $newAnimalsPets->getValues());
        $this->assertEqualsCanonicalizing([Animal::Lion], $animalsPets->getValues());
    }

    public function testCreateFromNames():void
    {
        $animals = Animal::createFromNames('Cat', 'Dog');
        $this->assertEqualsCanonicalizing([Animal::Cat, Animal::Dog], $animals->getValues());
    }

    public function testCreateFromValues():void
    {
        $animals = Animal::createFromValues(Animal::Cat, Animal::Dog);
        $this->assertEqualsCanonicalizing([Animal::Cat, Animal::Dog], $animals->getValues());
    }

    public function testEquals():void
    {
        $this->assertTrue(Animal::equals(null, null));
        $this->assertFalse(Animal::equals(null, new Animal()));
        $this->assertFalse(Animal::equals(new Animal(), null));
        $this->assertTrue(Animal::equals(new Animal(), new Animal()));
        $this->assertTrue(Animal::equals(new Animal(Animal::Wolf), new Animal(Animal::Wolf)));
        $this->assertTrue(Animal::equals(new Animal(Animal::Wolf, Animal::Lion), new Animal(Animal::Lion, Animal::Wolf)));
        $this->assertFalse(Animal::equals(new Animal(Animal::Wolf), new Animal(Animal::Lion)));
        $this->assertFalse(Animal::equals(new Animal(Animal::Wolf, Animal::Dog), new Animal(Animal::Cat, Animal::Lion)));
        $this->assertFalse(Animal::equals(new Animal(Animal::Wolf), new Animal(Animal::Wolf, Animal::Lion)));
        $this->assertFalse(Animal::equals(new Animal(Animal::Wolf, Animal::Lion), new Animal(Animal::Wolf)));
    }
}