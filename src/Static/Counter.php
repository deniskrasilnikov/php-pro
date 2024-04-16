<?php

namespace Static;

class Counter
{
    protected static int $counter = 0;

    public static function staticIncrement(int $increment = 1): void
    {
        static::$counter += $increment;
    }

    public function increment(int $increment = 1): void
    {
        static::$counter += $increment;
    }

    public static function getCount(): int
    {
        return static::$counter;
    }
}
