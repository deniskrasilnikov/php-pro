<?php

declare(strict_types=1);

namespace App\Module\Shop\Service;

use App\Module\Shop\Entity\Edition;
use Doctrine\ORM\EntityManagerInterface;

readonly class EditionService
{
    public function __construct(private EntityManagerInterface $shopEntityManager)
    {
    }

    public function createEdition(
        string $name,
        string $isbn10,
        string $authorName,
        string $publisherName,
        int $price
    ): void {
        $edition = new Edition($name, $authorName, $publisherName, $isbn10, $price);

        $this->shopEntityManager->persist($edition);
        $this->shopEntityManager->flush();
    }
}