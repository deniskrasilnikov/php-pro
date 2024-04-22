<?php

declare(strict_types=1);

namespace Literato\Repository;

use Literato\Entity\Publisher;
use PDO;

readonly class PublisherRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function add(Publisher $publisher): void
    {
        $statement = $this->pdo->prepare('INSERT INTO publisher (name, address) VALUES (:name, :address)');
        $statement->execute([
            ':name' => $publisher->getName(),
            ':address' => $publisher->getAddress(),

        ]);
        $publisher->setId((int)$this->pdo->lastInsertId());
    }

    public function findByName(string $publisherName): ?Publisher
    {
        $statement = $this->pdo
            ->prepare('SELECT * FROM publisher WHERE name = ?');
        $statement->execute([$publisherName]);

        $foundPublisher = $statement->fetchObject();

        if (!$foundPublisher) {
            return null;
        }

        $publisher = new Publisher();
        $publisher->setId($foundPublisher->id);
        $publisher->setName($foundPublisher->name);
        $publisher->setAddress($foundPublisher->address);

        return $publisher;
    }
}