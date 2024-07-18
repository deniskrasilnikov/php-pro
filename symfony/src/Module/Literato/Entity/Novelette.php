<?php

declare(strict_types=1);

namespace App\Module\Literato\Entity;

use Doctrine\ORM\Mapping\Entity;

#[Entity]
class Novelette extends Book
{
    public function validateText(): bool
    {
        return !empty($this->text);
    }
}