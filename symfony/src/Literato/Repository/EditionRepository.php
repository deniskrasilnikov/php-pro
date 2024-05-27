<?php

declare(strict_types=1);

namespace Literato\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Literato\Entity\Edition;

class EditionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Edition::class);
    }

    /**
     * Get best-selling editions
     * @return Edition[]
     */
    public function findBestSellers(int $bestSellerCount, string $publisherName = null): array
    {
        $queryBuilder = $this->createQueryBuilder('e')
            ->orderBy('e.soldCopiesCount', 'DESC');

        if ($publisherName) {
            $queryBuilder
                ->join('e.publisher', 'p')
                ->where('p.name = :name')
                ->setParameter('name', $publisherName);
        }

        $query = $queryBuilder->getQuery()
            ->setMaxResults($bestSellerCount);

        return $query->getResult();
    }
}