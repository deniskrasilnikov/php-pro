<?php

declare(strict_types=1);

namespace Literato\Exceptions;

use RuntimeException;

class BookValidationException extends RuntimeException
{
    public function __construct(string $message = 'Book object has invalid data')
    {
        parent::__construct($message);
    }
}