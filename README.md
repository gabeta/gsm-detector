[![Build Status](https://travis-ci.org/gabeta/gsm-detector.svg?branch=main)](https://travis-ci.org/gabeta/gsm-detector)

## A propos

Gsm Detector is a PHP package which allows to know the name of the GSM network
of a given phone number.

## Installation

Compatible with PHP >= 7.2 

```bash
composer require gabeta/gsm-detector
```

## How it works

You must initiate the **GsmDetector** class with an array containing the names of
GSM networks and their different prefixes.

**Code d'exmple:**
```php
use Gabeta\GsmDetector\GsmDetector;

$gsmDetector = new GsmDetector([
    'orange' => [
        'fix' => ['22', '35'],
        'mobile' => ['09', '88']
    ],
    'mtn' => [
        'fix' => ['23', '24'],
        'mobile' => ['04', '05']
    ],
]);

```

Here we instantiate our class with the GSM **mtn and orange** networks
and their different prefixes. Each GSM network defined in our table must have
the "fix" and / or "mobile" keys to define our various prefixes.

```php
use Gabeta\GsmDetector\GsmDetector;

$gsmDetector = new GsmDetector([
    'orange' => [
        'fix' => ['22', '35'],
        'mobile' => ['09', '88']
    ],
    'mtn' => [
        'fix' => ['23', '24'],
        'mobile' => ['04', '05']
    ],
]);

$gsmDetector->isMtnFix('23000000') // true

$gsmDetector->isMtnFix('24000000') // true

$gsmDetector->isMtnFix('04000000') // false

$gsmDetector->isMtnMobile('04000000') // true

$gsmDetector->isOrangeFix('22000000') // true

$gsmDetector->isOrangeFix('35000000') // true

$gsmDetector->isOrangeMobile('35000000') // false

$gsmDetector->isMtn('04000000') // true

$gsmDetector->isMtn('24000000') // true
        
$gsmDetector->isMtn('35000000') // false
        
$gsmDetector->isMtn('08000000') // false

$gsmDetector->isMtn('23000000') // true

$gsmDetector->isOrange('88000000') // true

$gsmDetector->isOrange('09000000') // true

$gsmDetector->isOrange('22000000') // true

```

For each new name of GSM defines three methods are created:

* is{Gsm}
* is{Gsm}Fix
* is{Gsm}Mobile

For moov for example we will therefore have:

* isMoov
* isMoovFix
* isMoovMobile
