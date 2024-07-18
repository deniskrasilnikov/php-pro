<?php

declare(strict_types=1);

namespace App\Module\Shop\Entity;

use App\Module\Shop\Repository\EditionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: EditionRepository::class)]
#[Table(name: 'edition')]
class Edition
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: Types::INTEGER)]
    private int $id;

    public function __construct(
        #[Column(length: 200)]
        private readonly string $name,

        #[Column(length: 150)]
        private readonly string $authorName,

        #[Column(length: 150)]
        private readonly string $publisherName,

        #[Column(length: 13, unique: true)]
        private readonly string $isbn10,

        #[Column(type: 'smallint')]
        private readonly int $price
    ) {
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getPublisherName(): string
    {
        return $this->publisherName;
    }

    public function getIsbn10(): string
    {
        return $this->isbn10;
    }

    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): int
    {
        return $this->id;
    }
}