Doctrine Boolean enum
=====================

[![Build Status](https://travis-ci.org/jaroslavtyc/doctrineum-boolean.svg?branch=master)](https://travis-ci.org/jaroslavtyc/doctrineum-boolean)
[![Test Coverage](https://codeclimate.com/github/jaroslavtyc/doctrineum-boolean/badges/coverage.svg)](https://codeclimate.com/github/jaroslavtyc/doctrineum-boolean/coverage)
[![License](https://poser.pugx.org/doctrineum/boolean/license)](https://packagist.org/packages/doctrineum/boolean)

## About
Adds [Enum](http://en.wikipedia.org/wiki/Enumerated_type) to [Doctrine ORM](http://www.doctrine-project.org/)
(can be used as a `@Column(type="boolean_enum")`).

##Usage

```php
<?php

use Doctrine\ORM\Mapping as ORM;
use Doctrineum\Boolean\BooleanEnum;

/**
 * @ORM\Entity()
 */
class Account
{
    /**
     * @var int
     * @ORM\Id() @ORM\GeneratedValue(strategy="AUTO") @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var BooleanEnum
     * @ORM\Column(type="boolean_enum")
     */
    private $activated;
    
    public function __construct()
    {
        $this->activated = BooleanEnum::getEnum(false);
    }

    /**
     * @return BooleanEnum
     */
    public function getActivated()
    {
        return $this->activated;
    }

    /**
     * @param BooleanEnum $activated
     */
    public function setActivated(BooleanEnum $activated)
    {
        $this->activated = $activated;
    }
}

$account = new Account();
$account->setActivated(BooleanEnum::getEnum(true));

/** @var \Doctrine\ORM\EntityManager $entityManager */
$entityManager->persist($account);
$entityManager->flush();
$entityManager->clear();

/** @var Account[] $accounts */
$accounts = $entityManager->createQuery(
    "SELECT a FROM Account a WHERE a.activated"
)->getResult();

var_dump($accounts[0]->getActivated()->getValue()); // true;
```

##Installation

Add it to your list of Composer dependencies (or by manual edit your composer.json, the `require` section)

```sh
composer require jaroslavtyc/doctrineum-boolean
```

Note: NULL is prohibited. NULL means "I don't know" and that is not FALSE neither TRUE.
If you want to use NULL, as lets say FALSE, do that conversion by your own, because you are the only one "who knows".

## Doctrine integration

Register new DBAL type:

```php
<?php

use Doctrineum\Boolean\BooleanEnumType;

BooleanEnumType::registerSelf();
```

When using Symfony with Doctrine you can do the same as above by configuration:

```yaml
# app/config/config.yml

# Doctrine Configuration
doctrine:
    dbal:
        # ...
        mapping_types:
            boolean_enum: boolean_enum
        types:
            boolean_enum: Doctrineum\Boolean\BooleanEnumType
```
