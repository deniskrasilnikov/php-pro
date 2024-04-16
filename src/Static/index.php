<?php

require_once 'Counter.php';
require_once 'ProgressiveCounter.php';

use Static\Counter;
use Static\ProgressiveCounter;

echo "STATIC VARIABLE USAGE EXAMPLE\n";

function sayHello(): int
{
    static $sayHelloCount = 0;
    echo "\nHello! ";
    return ++$sayHelloCount;
}

echo sayHello();
echo sayHello();
echo sayHello();

echo "\n";

echo "\n\nSTATIC METHODS USAGE EXAMPLE\n";

Counter::staticIncrement();
$counter = new Counter();
$counter->increment();

ProgressiveCounter::staticIncrement(7);
ProgressiveCounter::staticDecrement();

echo "\nCount: " . Counter::getCount();
echo "\nProgressive count: " . ProgressiveCounter::getCount();
echo "\n";