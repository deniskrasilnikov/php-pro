<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Literato\Entity\Author;
use Literato\Entity\Book;
use Literato\Entity\Enum\Genre;
use Literato\Entity\Novelette;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{
    #[Route('/book/{id}', name: 'app_book')]
    public function index(string $id, EntityManagerInterface $entityManager): Response
    {
        $book = $entityManager->getRepository(Book::class)->find($id);

        return new JsonResponse($book->getFullInfo());
    }

    #[Route('/book/{firstName}/{lastName}', name: 'app_book_create')]
    public function create(string $firstName, string $lastName, EntityManagerInterface $entityManager): Response
    {
        $author = $entityManager->getRepository(Author::class)->findOneBy([
            'firstName' => $firstName,
            'lastName' => $lastName
        ]);

        if (!$author) {
            throw new NotFoundHttpException('Author not found');
        }

        $faker = Factory::create();

        $book = new Novelette();
        $book->setName($faker->text(20));
        $book->setIsbn10($faker->isbn10());
        $book->setText($faker->text());
        $book->setGenres([Genre::Romance, Genre::Thriller]);
        $author->addBook($book);
        $entityManager->persist($book);
        $entityManager->flush();

        return new JsonResponse($book->getFullInfo());
    }
}
