<?php

declare(strict_types=1);

namespace App\Module\Literato\Entity\Enum;

use App\Module\Literato\Entity\Novel;
use App\Module\Literato\Entity\Novelette;

enum BookType: string
{
    public const ENTITY_MAP = [
        self::Novelette->name => Novelette::class,
        self::Novel->name => Novel::class,
    ];

    case Novelette = 'Novelette';
    case Novel = 'Novel';

    public function entity(): Novelette|Novel
    {
        return new (self::ENTITY_MAP[$this->name])();
    }
}