<?php

declare(strict_types=1);

namespace Literato\Entity\Enum;

enum Genre: string
{
    case NONE = 'n/a';
    case SciFi = 'Science fiction';
    case Adventure = 'Adventure';
    case Thriller = 'Thriller';
    case Horror = 'Horror';
    case LowFantasy = 'Low fantasy';
    case DarkFantasy = 'Dark fantasy';
    case MagicalRealism = 'Magical realism';
    case Romance = 'Romance';
}