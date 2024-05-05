<?php

declare(strict_types=1);

namespace Literato\PDO\Repository;

use Literato\Entity\Author;
use PDO;

readonly class AuthorRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function add(Author $author): void
    {
        $statement = $this->pdo->prepare(
            "INSERT INTO author (first_name, last_name) 
                VALUES (:firstName, :lastName)"
        );
        $statement->execute([
            ':firstName' => $author->getFirstName(),
            ':lastName' => $author->getLastName(),
        ]);
        $author->setId((int)$this->pdo->lastInsertId());
    }

    public function delete(Author $author): void
    {
        $statement = $this->pdo->prepare('DELETE FROM author WHERE id = :authorId');
        $statement->execute(['authorId' => $author->getId()]);
    }

    public function update(Author $author): void
    {
        // todo
    }
}