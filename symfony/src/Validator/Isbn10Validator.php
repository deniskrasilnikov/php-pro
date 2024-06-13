<?php

declare(strict_types=1);

namespace App\Validator;

use Biblys\Isbn\Isbn;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class Isbn10Validator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof Isbn10) {
            throw new UnexpectedTypeException($constraint, Isbn10::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        try {
            Isbn::convertToIsbn10($value);
        } catch (\Exception $e) {
            $this->context->buildViolation($e->getMessage())->addViolation();
        }
    }
}