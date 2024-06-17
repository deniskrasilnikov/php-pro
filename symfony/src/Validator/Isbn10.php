<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class Isbn10 extends Constraint
{
    public function __construct(?array $groups = null, $payload = null)
    {
        parent::__construct([], $groups, $payload);
    }
}