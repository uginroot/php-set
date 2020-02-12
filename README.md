# Install
```bash
composer require uginroot/php-set:^1.0
```

# Usage
```php
use Uginroot\PhpSet\SetAbstract;use Uginroot\PhpSet\SetImmutableAbstract;

class Animals extends SetAbstract{
    const Dog = 1;
    const Cat = 2;
    const Wolf = 3;
    const Lion = 4;
}

$animalsPets = new Animals(Animals::Dog, Animals::Cat);

// Compare
$animalsPets->in(Animals::Dog); // true
$animalsPets->is(Animals::Dog); // true

$animalsPets->in(Animals::Dog, Animals::Wolf); // true
$animalsPets->is(Animals::Dog, Animals::Wolf); // false

$animalsPets->equal(Animals::Dog, Animals::Cat); // true
$animalsPets->equal(Animals::Cat, Animals::Dog); // true
$animalsPets->equal([Animals::Cat, Animals::Dog]); // true
$animalsPets->equal(new Animals(Animals::Dog, Animals::Cat)); // true
$animalsPets->equal(new Animals(Animals::Dog), new Animals(Animals::Cat)); // true

$animalsPets->equal(Animals::Cat); // false
$animalsPets->equal(Animals::Dog); // false
$animalsPets->equal(Animals::Dog, Animals::Cat, Animals::Lion); // false

// Static methods
Animals::getNameVariants(); // ['Dog', 'Cat', 'Wolf', 'Lion']
Animals::getValueVariants(); // [1, 2, 3, 4]
Animals::getValueName(Animals::Dog); // 'Dog'
Animals::getNameValue('Dog'); // Animal::Dog (1)

// Public methods
$animalsPets->getValues(); // [1, 2]
$animalsPets->getNames(); // ['Dog', 'Cat']

$animalsPets->addValue(Animals::Wolf);
$animalsPets->addValueByName('Lion');
$animalsPets->getNames(); // ['Dog', 'Cat', 'Wolf', 'Lion']

$animalsPets->removeValue(Animals::Wolf);
$animalsPets->removeValueByName('Lion');
$animalsPets->getNames(); // ['Dog', 'Cat']

$animalsPets->setValues(Animals::Wolf, Animals::Lion);
$animalsPets->getNames(); // ['Wolf', 'Lion']
$animalsPets->setValues(new Animals(Animals::Dog, Animals::Cat));
$animalsPets->getNames(); // ['Dog', 'Cat']

$animalsPets->setNames('Wolf', 'Lion');
$animalsPets->getNames(); // ['Wolf', 'Lion']

class AnimalsImmutable extends SetImmutableAbstract{
    const Dog = 1;
    const Cat = 2;
    const Wolf = 3;
    const Lion = 4;
}
$animalsImmutablePets = new AnimalsImmutable(Animals::Dog, Animals::Cat);
$animalsImmutableWild = $animalsImmutablePets->setValues(Animals::Wolf, Animals::Lion);

$animalsImmutablePets->getNames(); // ['Dog', 'Cat']
$animalsImmutableWild->getNames(); // ['Wolf', 'Lion']

```

# Exceptions
```php
use Uginroot\PhpSet\SetAbstract;
use Uginroot\PhpSet\Exception\DuplicateValueException;
use Uginroot\PhpSet\Exception\IncorrectValueException;
use Uginroot\PhpSet\Exception\IncorrectNameException;

class Animals extends SetAbstract{
    const Dog = 1;
    const Cat = 2;
}
$animals = new Animals(Animals::Dog, Animals::Cat);

try{ Animals::getValueName(3);         } catch (IncorrectValueException $e){}
try{ $animals->addValue(3);            } catch (IncorrectValueException $e){}

try{ Animals::getNameValue('Wolf');    } catch (IncorrectNameException $e){}
try{ $animals->addValueByName('Wolf'); } catch (IncorrectNameException $e){}


class Buttons extends SetAbstract{
    const Cancel = 1;
    const Accept = 1;
    const Reject = 1;
}
try{ Buttons::getNameVariants();             } catch (DuplicateValueException $e){}
try{ Buttons::getValueVariants();            } catch (DuplicateValueException $e){}
try{ Buttons::getValueName(Buttons::Cancel); } catch (DuplicateValueException $e){}
try{ Buttons::getNameValue('Dog');           } catch (DuplicateValueException $e){}
try{ new Buttons();                          } catch (DuplicateValueException $e){}


```

# Run tests
```bash
composer test
```

# Run benchmark
```bash
composer benchmark
```