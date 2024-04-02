<?php

require_once 'Book.php';

class Novelette extends Book
{
    /**
     * @throws Exception
     */
    protected function validateText(string $text): void
    {
        if ($text == '') {
            throw new Exception('Novelette text must not be empty');
        }
    }
}