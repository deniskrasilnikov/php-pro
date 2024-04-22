<?php

declare(strict_types=1);

namespace Literato\Entity;

class Author
{
    private int $id;
    private string $firstName = '';
    private string $lastName = '';
    /** @var Book[]  */
    private array $books;
    /** @var Publisher[]  */
    private array $publishers = [];

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