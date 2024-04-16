<?php

declare(strict_types=1);

namespace Generators;

use Exception;
use Literato\Author;
use Literato\Book;
use Literato\Genre;
use Literato\Novelette;
use Literato\Publisher;

final class BookDbRepository
{
    private const BOOK_COUNT = 130000;

    public function findAllBooks(): array
    {
        $books = [];

        for ($i = 0; $i < self::BOOK_COUNT; $i++) {
            try {
                $book = $this->readBook();

                if ($this->validateISBN($book->getIsbn())) {
                    $books[] = $book;
                }
            } catch (Exception $e) {
                die($e);
            }
        }

        return $books;
    }

    public function findAllBooksAsIterable(): iterable
    {
        for ($i = 0; $i < self::BOOK_COUNT; $i++) {
            try {
                $book = $this->readBook();
                if ($this->validateISBN($book->getIsbn())) {
                    yield $book;
                }
            } catch (Exception $e) {
                die($e);
            }
        }
    }

    /**
     * @throws Exception
     */
    private function readBook(): Book
    {
        $nameParts[] = bin2hex(random_bytes(10));

        $book = new Novelette();
        $book->setName(implode(' ', $nameParts));
        $book->setIsbn('978-3-16-148410-0');
        $book->setText('Lorem ipsum dolor sit amet, consectetuer adipiscing elit');
        $book->setGenres([Genre::Romance, Genre::Thriller]);
        $author = new Author();
        $author->setFirstName('John ');
        $author->setLastName(' Bon');
        $book->setAuthor($author);
        $publisher = new Publisher();
        $publisher->setName('Williams Publishing');
        $publisher->setAddress('Addrs.');
        $book->setPublisher($publisher);

        return $book;
    }

    private function validateISBN(string $isbn): bool
    {
        preg_match('/^(?=(?:\D*\d){10}(?:(?:\D*\d){3})?$)[\d-]+$/', $isbn, $matches);

        return count($matches) > 0;
    }
}