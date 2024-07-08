<?php

declare(strict_types=1);

namespace Literato\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\{Column, Entity, GeneratedValue, Id, JoinTable, ManyToMany, OneToMany, Table};
use Symfony\Component\Serializer\Attribute as Serialize;

#[Entity]
#[Table(name: 'author')]
class Author
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: Types::INTEGER)]
    #[Serialize\Groups(['author_item', 'author_list'])]
    private int $id;

    #[Column(name: 'first_name', length: 50)]
    #[Serialize\Groups(['author_item', 'author_list'])]
    private string $firstName = '';

    #[Column(name: 'last_name', length: 50)]
    #[Serialize\Groups(['author_item', 'author_list'])]
    private string $lastName = '';

    /** @var Collection<int, Book> */
    #[OneToMany(targetEntity: Book::class, mappedBy: 'author', cascade: ["persist"] )]
    #[Serialize\Groups(['author_books'])]
    private Collection $books;

    /** @var Collection<int, Publisher> */
    #[ManyToMany(targetEntity: Publisher::class, inversedBy: 'authors')]
    #[JoinTable(name: 'author_publisher')]
    private Collection $publishers;

    public function __construct()
    {
        $this->books = new ArrayCollection();
        $this->publishers = new ArrayCollection();
    }

    public function getBooksCount(): int
    {
        return count($this->books);
    }

    public function addBook(Book $book): void
    {
        $book->setAuthor($this);
        $this->books[] = $book;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
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

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return Collection
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }
}