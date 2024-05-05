<?php

declare(strict_types=1);

namespace Literato\PDO\Repository;

use Literato\Entity\Edition;
use PDO;

readonly class EditionRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function add(Edition $edition): void
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO edition (book_id, publisher_id, price, author_reward_copy, sold_copies_count, status) 
                VALUES (:bookId, :publisherId, :price, :authorRewardCopy, :soldCopiesCount, :status)'
        );
        $statement->execute([
            ':bookId' => $edition->getBook()->getId(),
            ':publisherId' => $edition->getPublisher()->getId(),
            ':price' => $edition->getPrice(),
            ':authorRewardCopy' => $edition->getAuthorRewardPerCopy(),
            ':soldCopiesCount' => $edition->getSoldCopiesCount(),
            ':status' => $edition->getStatus()->value,

        ]);
        $edition->setId((int)$this->pdo->lastInsertId());
    }
}