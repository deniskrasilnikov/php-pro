<?php

declare(strict_types=1);

namespace App\Module\Literato\Repository;

use App\Module\Literato\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

class BookRepository extends ServiceEntityRepository
{
    private const BOOKS_PER_PAGE = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function findPage(int $page = 1)
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.id')
            ->getQuery()
            ->setFirstResult(self::BOOKS_PER_PAGE * $page - self::BOOKS_PER_PAGE)
            ->setMaxResults(self::BOOKS_PER_PAGE)
            ->getResult();
    }

    /**
     * Possible dangerous $namePart values to test:
     *
     *   n/a ' UNION select concat(email, roles) from user #
     *   n/a ' ; DROP TABLE test  #
     *
     * @throws Exception
     */
    public function findByNamePart(string $namePart): array
    {
        return $this->getEntityManager()
            ->getConnection()
            ->executeQuery('SELECT b.name FROM book b WHERE b.name LIKE :name', ['name' => "%$namePart%"])
            ->fetchAllAssociative();
    }

}