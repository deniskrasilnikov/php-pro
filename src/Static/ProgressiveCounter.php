<?php

namespace Static;

class ProgressiveCounter extends Counter
{
    protected static int $counter = 0;

    public static function staticDecrement(int $decrement = 1)
    {
        static::$counter -= $decrement;
    }
}