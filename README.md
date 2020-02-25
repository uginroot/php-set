# Install
```bash
composer require uginroot/php-set:^2.0
```

# Usage
```php
use Uginroot\PhpSet\SetAbstract;

class Animals extends SetAbstract{
    const Dog = 1;
    const Cat = 2;
    const Wolf = 3;
    const Lion = 4;
}

// Static methods
Animals::getNameVariants();     // ['Dog', 'Cat', 'Wolf', 'Lion']
Animals::getValueVariants();    // [1, 2, 3, 4]
Animals::getName(Animals::Dog); // 'Dog'
Animals::getValue('Dog');       // 1

$animalsPets = new Animals(Animals::Dog, Animals::Cat);
$animalsPets = Animals::createFromNames('Dog', 'Cat');

// Compare
$animalsPets->in(Animals::Dog);                  // true
$animalsPets->in(Animals::Dog, Animals::Wolf);   // true

$animalsPets->is(Animals::Dog);                  // true
$animalsPets->is(Animals::Dog, Animals::Wolf);   // false

$animalsPets->equal(Animals::Dog, Animals::Cat); // true
$animalsPets->equal(Animals::Cat);               // false

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

class Animals extends SetAbstract{
    const Dog = 1;
    const Cat = 2;
}
$animals = new Animals(Animals::Dog, Animals::Cat);

try{ Animals::getName(3);    } catch (IncorrectValueException $e){}
try{ $animals->addValues(3); } catch (IncorrectValueException $e){}

try{ Animals::getValue('Wolf');  } catch (IncorrectNameException $e){}
try{ $animals->addNames('Wolf'); } catch (IncorrectNameException $e){}


class Buttons extends SetAbstract{
    const Cancel = 1;
    const Accept = 1;
    const Reject = 1;
}
try{ Buttons::getNameVariants();        } catch (DuplicateValueException $e){}
try{ Buttons::getValueVariants();       } catch (DuplicateValueException $e){}
try{ Buttons::getName(Buttons::Cancel); } catch (DuplicateValueException $e){}
try{ Buttons::getValue('Dog');          } catch (DuplicateValueException $e){}
try{ new Buttons();                     } catch (DuplicateValueException $e){}


```

# Run tests
```bash
composer test
```

# Run benchmark
```bash
composer benchmark
```