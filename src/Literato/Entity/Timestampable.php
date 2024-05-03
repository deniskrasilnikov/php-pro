<?php

declare(strict_types=1);

namespace Literato\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping\Column;

trait Timestampable
{
    #[Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    protected ?DateTimeInterface $createdAt = null;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    #[Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    protected ?DateTimeInterface $updatedAt = null;

    private function initTimestampable(): void
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface|null $createdAt
     */
    public function setCreatedAt(?DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeInterface|null $updatedAt
     */
    public function setUpdatedAt(?DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
