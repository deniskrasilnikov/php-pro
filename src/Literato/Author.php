<?php

declare(strict_types=1);

namespace Literato;

class Author
{
    use PublisherAware;

    private string $firstName = '';
    private string $lastName = '';
    private array $books; // author AGGREGATES his/her books

    public function getBooksCount(): int
    {
        return count($this->books);
    }

    public function addBook(Book $book): void
    {
        $book->setAuthor($this); // AGGREGATION
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
}