<?php

declare(strict_types=1);

namespace Literato\Entity;

use Doctrine\ORM\Mapping\Entity;
use Exception;
use Literato\Entity\Exception\BookValidationException;

#[Entity]
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