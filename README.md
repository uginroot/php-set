# Install
```bash
composer require uginroot/php-set:^2.4
```

# Usage
```php
use Uginroot\PhpSet\SetAbstract;
use Uginroot\PhpSet\Choice;

class Animals extends SetAbstract{
    protected const Other = 0; // Is not considered

    public const Dog = 1;
    public const Cat = 2;
    public const Wolf = 3;
    public const Lion = 4;
}

/** @var Choice $choice */
$choice = Animals::getChoice();
$choice->getNames(); // ['Dog', 'Cat', 'Wolf', 'Lion']
$choice->getValues(); // [1, 2, 3, 4]
$choice->getName(1); // 'Dog'
$choice->getValue('Dog'); // 1
$choice->findNames(1, 3); // ['Dog', 'Wolf']
$choice->findValues('Dog', 'Wolf'); // [1, 3]
$choice->extractorValues(1, 3); // [1, 3]
$choice->extractorNames('Dog', 'Wolf'); // ['Dog', 'Wolf']

$animalsPets = new Animals(Animals::Dog, Animals::Cat);
$animalsPetsNames = Animals::createFromNames('Dog', 'Cat');

// Compare
$animalsPets->in(Animals::Dog);                  // true
$animalsPets->in(Animals::Dog, Animals::Wolf);   // true

$animalsPets->is(Animals::Dog);                  // true
$animalsPets->is(Animals::Dog, Animals::Wolf);   // false

$animalsPets->equal(Animals::Dog, Animals::Cat); // true
$animalsPets->equal(Animals::Cat);               // false
$animalsPets->equal($animalsPetsNames);          // true

Animals::equals(new Animals(Animals::Wolf), new Animals(Animals::Wolf)); // true

// Current names|values
$animalsPets->getValues(); // [1, 2]
$animalsPets->getNames();  // ['Dog', 'Cat']

// Create add return modified object
$animalsPets->addValues(Animals::Wolf);
$animalsPets->addNames('Lion');

$animalsPets->removeValues(Animals::Wolf);
$animalsPets->removeNames('Lion');

$animalsPets->setValues(Animals::Wolf, Animals::Lion);
$animalsPets->setNames('Wolf', 'Lion');

```

# Exceptions
```php
use Uginroot\PhpSet\SetAbstract;
use Uginroot\PhpSet\Exception\DuplicateValueException;
use Uginroot\PhpSet\Exception\IncorrectValueException;
use Uginroot\PhpSet\Exception\IncorrectNameException;
use Uginroot\PhpSet\Choice;

class Animals extends SetAbstract{
    public const Dog = 1;
    public const Cat = 2;
}

/** @var Choice $choice */
$choice = Animals::getChoice();

try{ $choice->getName(3); } catch (IncorrectValueException $e){}
try{ $choice->getValue('Wolf'); } catch (IncorrectNameException $e){}

class Buttons extends SetAbstract{
    public const Cancel = 1;
    public const Accept = 1;
    public const Reject = 1;
}

try{ Buttons::getChoice(); } catch (DuplicateValueException $e){}
```