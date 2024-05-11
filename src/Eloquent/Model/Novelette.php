<?php

declare(strict_types=1);

namespace Eloquent\Model;

use Literato\Entity\Exception\BookValidationException;

class Novelette extends Book
{
    /**
     * @throws BookValidationException
     */
    protected function validateText(string $text): void
    {
        parent::validateText($text);

        if ($text == '') {
            throw new BookValidationException('Novelette text must not be empty');
        }
    }
}