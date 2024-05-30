<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Literato\Entity\Author;
use Literato\Entity\Book;
use Literato\Manager\BookManager;
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

        if (!$book) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($book->getFullInfo());
    }

    #[Route('/book/{firstName}/{lastName}', name: 'app_book_create')]
    public function create(string $firstName, string $lastName, EntityManagerInterface $entityManager, BookManager $bookManager): Response
    {
        $author = $entityManager->getRepository(Author::class)->findOneBy([
            'firstName' => $firstName,
            'lastName' => $lastName
        ]);

        if (!$author) {
            throw new NotFoundHttpException('Author not found');
        }

        return new JsonResponse($bookManager->createNovelette($author)->getFullInfo());
    }
}
