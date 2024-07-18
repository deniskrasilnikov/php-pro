<?php

declare(strict_types=1);

namespace App\Module\Literato\Entity;

trait PublisherAware
{
    protected Publisher $publisher;

    public function setPublisher(Publisher $publisher): void
    {
        $this->publisher = $publisher;
    }

}