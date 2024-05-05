<?php

declare(strict_types=1);

namespace Literato\PDO\Repository;

use Literato\Entity\Book;
use Literato\Entity\Enum\Genre;
use Literato\Entity\Novel;
use Literato\Entity\Novelette;
use Literato\PDO\Repository\Exception\EntityIdException;
use PDO;

readonly class BookRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function add(Book $book): void
    {
        if (null == $book->getAuthor()->getId()) {
            throw new EntityIdException('Author entity does not have id');
        }

        $statement = $this->pdo->prepare(
            'INSERT INTO book (name, isbn10, text, author_id, genres, type) 
                VALUES (:name, :isbn, :text, :authorId, :genres, :type)'
        );
        $statement->execute([
            ':name' => $book->getName(),
            ':isbn' => $book->getIsbn10(),
            ':text' => $book->getText(),
            ':authorId' => $book->getAuthor()->getId(),
            ':genres' => implode(",", array_column($book->getGenres(), 'value')),
            ':type' => $book->getType(),
        ]);
        $book->setId((int)$this->pdo->lastInsertId());
    }

    public function delete(Book $book): void
    {
        $statement = $this->pdo->prepare('DELETE FROM book WHERE id = :bookId');
        $statement->execute(['bookId' => $book->getId()]);
    }

    public function update(Book $book): void
    {
        $statement = $this->pdo->prepare(
            'UPDATE book SET name=:name, isbn10=:isbn10 WHERE id=:id'
        );

        $statement->execute([
            ':id' => $book->getId(),
            ':name' => $book->getName(),
            ':isbn10' => $book->getIsbn10(),
        ]);
    }

    public function findByIsbn10(string $isbn10): ?Book
    {
        $statement = $this->pdo
            ->prepare('SELECT id, type, name, text FROM book WHERE isbn10 = ?');
        $statement->execute([$isbn10]);

        $foundBook = $statement->fetchObject();

        if (!$foundBook) {
            return null;
        }
        /** @var Book $book */
        $book = match ($foundBook->type) {
            'Novel' => new Novel(),
            'Novelette' => new Novelette(),
        };
        $book->setId($foundBook->id);
        $book->setIsbn10($isbn10);
        $book->setName($foundBook->name);
        $book->setGenres([Genre::SciFi, Genre::MagicalRealism]);
        $book->setText($foundBook->text);

        return $book;
    }
}