<?php

require_once 'Food.php';
require_once 'Stomach.php';

class Human
{
    private Stomach $stomach;

    public function __construct(private readonly string $name)
    {
        echo "\n$this->name says: I am born";
        $this->stomach = new Stomach(); // COMPOSITION
    }

    public function eat(Food $food): void
    {
        $this->stomach->digestFood($food);
    }

    public function __destruct()
    {
        echo "\n$this->name says: Bye bye!";
    }
}