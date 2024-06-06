<?php

declare(strict_types=1);

namespace Literato\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\{Column, Entity, GeneratedValue, Id, JoinTable, ManyToMany, OneToMany, Table};

#[Entity]
#[Table(name: 'author')]
class Author
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: Types::INTEGER)]
    private int $id;

    #[Column(name: 'first_name', length: 50)]
    private string $firstName = '';

    #[Column(name: 'last_name', length: 50)]
    private string $lastName = '';

    /** @var Collection<int, Book> */
    #[OneToMany(targetEntity: Book::class, mappedBy: 'author', cascade: ["persist"] )]
    private Collection $books;

    /** @var Collection<int, Publisher> */
    #[ManyToMany(targetEntity: Publisher::class, inversedBy: 'author')]
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
}