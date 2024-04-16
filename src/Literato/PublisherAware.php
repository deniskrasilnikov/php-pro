<?php

declare(strict_types=1);

namespace Literato;

trait PublisherAware
{
    protected Publisher $publisher;

    public function setPublisher(Publisher $publisher): void
    {
        $this->publisher = $publisher;
    }

}