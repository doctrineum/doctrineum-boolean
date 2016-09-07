[![Build Status](https://travis-ci.org/jaroslavtyc/doctrineum-boolean.svg?branch=master)](https://travis-ci.org/jaroslavtyc/doctrineum-boolean)
[![Test Coverage](https://codeclimate.com/github/jaroslavtyc/doctrineum-boolean/badges/coverage.svg)](https://codeclimate.com/github/jaroslavtyc/doctrineum-boolean/coverage)

# About
[Doctrine](http://www.doctrine-project.org/) [enum](http://en.wikipedia.org/wiki/Enumerated_type) allowing bools only.

### Example
```php
$booleanEnum = BooleanEnum::getEnum(12345);
$booleanEnum->getValue() === true === (bool)$booleanEnum;

$enum = BooleanEnum::getEnum('  12 ');
$enum->getValue() === true;

$enum = BooleanEnum::getEnum(0);
$enum->getValue() === false;
"$enum" === '' === (string)false;

$enum = BooleanEnum::getEnum(0.0);
$enum->getValue() === false;

$enum = BooleanEnum::getEnum('0.0');
$enum->getValue() === true === (bool)'0.0'; // beware, casting string-float zero into boolean is true

$enum = BooleanEnum::getEnum(' ');
$enum->getValue() === true === (bool)' '; // beware, casting any white character into boolean is true

```

Note: NULL is prohibited. NULL means "I don't know" and that is not FALSE neither TRUE.
If you want to use NULL, as lets say FALSE, do that conversion by your own, because you are the only one "who knows".

# Doctrine integration
For details about new Doctrine type registration, see the parent project [Doctrineum](https://github.com/jaroslavtyc/doctrineum).
