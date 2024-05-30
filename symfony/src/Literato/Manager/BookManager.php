<?php

declare(strict_types=1);

namespace Literato\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Faker\Generator;
use Literato\Entity\Author;
use Literato\Entity\Enum\Genre;
use Literato\Entity\Novel;
use Literato\Entity\Novelette;
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