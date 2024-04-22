<?php

declare(strict_types=1);

namespace Literato\Entity;

trait Identity
{
    private int $id;

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}