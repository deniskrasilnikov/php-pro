<?php

declare(strict_types=1);

namespace Literato;

use Exception;
use Literato\Exceptions\BookValidationException;

class Novelette extends Book
{
    /**
     * @throws Exception
     */
    protected function validateText(string $text): void
    {
        if ($text == '') {
            throw new BookValidationException('Novelette text must not be empty');
        }
    }
}