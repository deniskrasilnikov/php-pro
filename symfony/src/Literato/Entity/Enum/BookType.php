<?php

declare(strict_types=1);

namespace Literato\Entity\Enum;

use Literato\Entity\Novel;
use Literato\Entity\Novelette;

enum BookType: string
{
    public const ENTITY_MAP = [
        self::Novelette->name => Novelette::class,
        self::Novel->name => Novel::class,
    ];

    case Novelette = 'Novelette';
    case Novel = 'Novel';

    public function entity(): object
    {
        return new (self::ENTITY_MAP[$this->name])();
    }
}