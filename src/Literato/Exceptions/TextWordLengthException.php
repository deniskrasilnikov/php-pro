<?php

declare(strict_types=1);

namespace Literato\Exceptions;

use Literato\Book;

class TextWordLengthException extends BookValidationException
{
    public function __construct(Book $book, int $wordLength)
    {
        parent::__construct(sprintf('The %s text must have at least %d words', $book::class, $wordLength));
    }
}