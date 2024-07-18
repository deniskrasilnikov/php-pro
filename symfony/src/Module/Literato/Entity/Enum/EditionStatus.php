<?php

namespace App\Module\Literato\Entity\Enum;

enum EditionStatus: string
{
    case InProgress = 'in_progress';
    case Published = 'published';
}