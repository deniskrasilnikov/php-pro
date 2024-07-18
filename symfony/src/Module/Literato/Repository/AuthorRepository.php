<?php

declare(strict_types=1);

namespace App\Module\Literato\Repository;

use App\Module\Literato\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AuthorRepository extends ServiceEntityRepository
{
    private const AUTHORS_PER_PAGE = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function findPage(int $page = 1, int $pageSize = self::AUTHORS_PER_PAGE)
    {
        $authors = $this->createQueryBuilder('a')
            ->orderBy('a.id')
            ->setFirstResult($pageSize * ($page - 1))
            ->setMaxResults($pageSize)
            ->getQuery()
            ->getResult();

        # штучний баг в лозіці бекенду
        array_unshift($authors, $authors[1]);

        return $authors;
    }
}