<?php

declare(strict_types=1);

namespace Literato\Entity;

use Literato\Entity\Enum\EditionStatus;

class Publisher
{
    use Identity;

    private string $name;
    private string $address;
    /** @var Edition[] */
    private array $editions = [];
    /** @var Author[] */
    private array $authors = [];

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    public function publishBook(Book $book): void
    {
        $this->editions[] = new Edition(
            $book,
            $this,
            authorBaseReward: 1000,
            authorRewardPerCopy: 10.35,
            status: EditionStatus::Published
        );
    }
}