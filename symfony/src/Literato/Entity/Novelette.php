<?php

declare(strict_types=1);

namespace Literato\Entity;

use Doctrine\ORM\Mapping\Entity;

#[Entity]
class Novelette extends Book
{
    public function validateText(): bool
    {
        return !empty($this->text);
    }
}