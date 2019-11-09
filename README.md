# cliffordvickrey/dogs-playing-poker

Every look at Cassius Marcellus Coolidge's seminal chef d'oeuvre *Dogs Playing Poker* and think,  "this is okay, but wouldn't it be nice if I could see the painting with any of 6.6 quadrillion possible card combinations?" No? Too bad! This library generates any one of 6,622,345,729,233,223,680 possible hand variations of dogs playing poker.

Online at https://www.cliffordvickrey.com/dogs-playing-poker/example

## Installation

Run the following to install this library:
```bash
$ composer require cliffordvickrey/dogs-playing-poker
```

## Requirements

* PHP 7.2 or above
* Imagick extension installed and enabled

## Usage

```php
// build the generator
$generator = DogsPlayingPokerGenerator::build();

// generate a painting at random ...
$painting = $generator->generate();

// ... or a specific ID for the same painting every time
$painting = $generator->generate(1);

// get the painting as a blob ...
$blob = $dogsPlayingPoker->getDogsAsBlob();

// ... or as a stream
$stream = fopen('php://temp', 'w');
$blob->writeDogsToResource($stream);
```
