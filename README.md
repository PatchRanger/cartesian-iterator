# cartesian-iterator
Iterator, returning the cartesian product of associative array of iterators. See https://en.wikipedia.org/wiki/Cartesian_product .

## Alternative implementations
- https://github.com/phpbench/phpbench/blob/master/lib/Benchmark/CartesianParameterIterator.php : A little bit more messy.
- https://github.com/bpolaszek/cartesian-product : Not so laconic - but covered by tests.
- https://stackoverflow.com/a/15973172 : Pretty simple though no iterator, plain array-stuff.

## Benchmark
https://github.com/PatchRanger/php-cartesian-benchmark

## Quickstart
```php
<?php

use PatchRanger\CartesianIterator;

require 'vendor/autoload.php';

$cartesianIterator = new CartesianIterator();
// The second argument controls the key of the corresponding value in the product array.
$cartesianIterator->attachIterator(new ArrayIterator([1,2]), 'test');
// No second argument means incremental numeration (indexed).
$cartesianIterator->attachIterator(new ArrayIterator(['foo', 'bar']));

$result = iterator_to_array($cartesianIterator, false);
print_r($result);
```
Result:
```
Array
(
    [0] => Array
        (
            [test] => 1
            [1] => foo
        )

    [1] => Array
        (
            [test] => 2
            [1] => foo
        )

    [2] => Array
        (
            [test] => 1
            [1] => bar
        )

    [3] => Array
        (
            [test] => 2
            [1] => bar
        )

)
```
