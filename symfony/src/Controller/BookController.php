<?php

namespace App\Controller;

use Literato\Service\Printing\PrinterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Literato\Entity\Author;
use Literato\Entity\Book;
use Literato\Manager\BookManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    /** Print book with given book id */
    #[Route('/book/{id}/printable', name: 'app_book_print')]
    public function print(PrinterInterface $printer, Book $book, Request $request): Response
    {
        $printer->print($book, $request->get('format'));

        return new Response();
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
