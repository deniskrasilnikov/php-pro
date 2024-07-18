<?php

declare(strict_types=1);

namespace App\Module\Literato\Entity\Enum;

enum Genre: string
{
    case SciFi = 'Science fiction';
    case Adventure = 'Adventure';
    case Thriller = 'Thriller';
    case Horror = 'Horror';
    case LowFantasy = 'Low fantasy';
    case DarkFantasy = 'Dark fantasy';
    case MagicalRealism = 'Magical realism';
    case Romance = 'Romance';
}