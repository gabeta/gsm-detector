## A propos

Gsm Detector est un package PHP qui permet de connaitre le nom du reseau GSM 
d'un numéro de téléphone donné.

## Installation

Compatible with PHP >= 7.2 

```bash
composer require gabeta/gsm-detector
```

## Comment ça marche

#### Instanciation

Vous devez intanciez la classe **GsmDetector** avec un tableau contenant le nom des 
réseaux GSM et leurs different prefix.

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

Ici nous instanciez notre classe avec les réseaux GSM **mtn et orange**
et leurs différents prefix. Chaque réseau GSM definit dans notre tableau devra avoir
les clés "fix" et/ou "mobile" pour définir nos différents préfix. 

#### Instanciation
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

$gsmDetector->isTogocel('04000000') // true

$gsmDetector->isTogocel('24000000') // true
        
$gsmDetector->isTogocel('35000000') // false
        
$gsmDetector->isTogocel('08000000') // false

$gsmDetector->isTogocel('23000000') // true

$gsmDetector->isOrange('88000000') // true

$gsmDetector->isOrange('09000000') // true

$gsmDetector->isOrange('22000000') // true

```

Pour chaque nouveau nom de GSM définit trois methodes sont créer:

* is{Gsm}
* is{Gsm}Fix
* is{Gsm}Mobile

Pour moov par exemple nous aurons donc:

* isMoov
* isMoovFix
* isMoovMobile

#### D'autres usage
