<?php

declare(strict_types=1);

namespace Literato\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Literato\Entity\Enum\EditionStatus;

#[Entity]
class Publisher
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: Types::INTEGER)]
    private int $id;

    #[Column(length: 200)]
    private string $name;

    #[Column(length: 500)]
    private string $address;

    /** @var Collection<int, Edition> */
    private Collection $editions;

    /** @var Collection<int, Author> */
    #[ManyToMany(targetEntity: Author::class, mappedBy: 'publishers')]
    private Collection $authors;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
        $this->editions = new ArrayCollection();
    }


    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }


    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

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

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }
}