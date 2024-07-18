<?php

declare(strict_types=1);

namespace App\Module\Literato\Manager;

use App\Module\Literato\Entity\Author;
use App\Module\Literato\Entity\Enum\Genre;
use App\Module\Literato\Entity\Novel;
use App\Module\Literato\Entity\Novelette;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Generator;
use Psr\Log\LoggerInterface;

readonly class BookManager
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
        private Generator $faker
    ) {
    }

    public function createNovelette(Author $author): Novelette
    {
        $book = new Novelette();
        $book->setName($this->faker->text(20));
        $book->setIsbn10($this->faker->isbn10());
        $book->setText($this->faker->text());
        $book->setGenres([Genre::Romance, Genre::Thriller]);
        $author->addBook($book);
        $this->entityManager->persist($book);
        $this->entityManager->flush();

        $this->logger->debug(
            "Created novelette {$book->getName()}",
            ['isbn10' => $book->getIsbn10(), 'author' => $author->getFullName()]
        );

        return $book;
    }

    public function createNovel(Author $author): Novel
    {
        $book = new Novel();
        $book->setName($this->faker->text(20));
        $book->setIsbn10($this->faker->isbn10());
        $book->setText($this->faker->text());
        $book->setGenres([Genre::SciFi, Genre::MagicalRealism]);
        $book->setSynopsis($this->faker->text(50));
        $author->addBook($book);
        $this->entityManager->persist($book);
        $this->entityManager->flush();

        $this->logger->debug(
            "Created novel {$book->getName()}",
            ['isbn10' => $book->getIsbn10(), 'author' => $author->getFullName()]
        );

        return $book;
    }
}