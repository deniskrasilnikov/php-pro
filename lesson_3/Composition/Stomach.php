<?php

require_once 'Food.php';

class Stomach
{
    public function __construct()
    {
        echo "\nStomach says: Gimme some food. I am hungry!";
    }

    public function __destruct()
    {
        echo "\nStomach says: Bye bye!";
    }

    public function digestFood(Food $food): void
    {
        echo "\nStomach says: I am digesting $food";
    }
}