<?php

declare(strict_types=1);

namespace App\Module\Literato\Event;

use App\Module\Literato\Entity\Edition;

readonly class EditionPublishedEvent
{
    public function __construct(
        private Edition $edition,
    )
    {
    }

    public function getEdition(): Edition
    {
        return $this->edition;
    }

}