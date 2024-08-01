<?php

declare(strict_types=1);

namespace App\Module\Literato\Entity;

use Doctrine\ORM\Mapping\{Column, Entity, GeneratedValue, Id, JoinColumn, ManyToOne, Table};
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Attribute as Serialize;
use Symfony\Component\Validator\Constraints as Assert;

#[Table(name: 'book_translation')]
#[Entity]
class BookTranslation
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: Types::INTEGER)]
    protected int $id;

    #[ManyToOne(targetEntity: Book::class, inversedBy: 'translations')]
    #[JoinColumn(name: 'book_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    protected Book $book;

    public function __construct(
        #[Column(length: 8)]
        private string $locale,

        #[Column(length: 150)]
        #[Assert\Length(min: 5, minMessage: 'Name must be at least {{ limit }} characters long',
        )]
        #[Serialize\Groups(['book_list'])]
        private string $name = ''
    ) {
    }

    public function setBook(Book $book): void
    {
        $this->book = $book;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }
}