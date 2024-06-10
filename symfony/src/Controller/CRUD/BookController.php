<?php

declare(strict_types=1);

namespace App\Controller\CRUD;

use Doctrine\ORM\EntityManagerInterface;
use Literato\Entity\Book;
use Literato\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

use const FILTER_VALIDATE_REGEXP;

/** CRUD-контролер для книг */
class BookController extends AbstractController
{
    /** Переглянути усі книги (з пагінацією) */
    #[Route('/books', name: 'app_crud_book_index', methods: 'GET')]
    public function index(
        BookRepository $books,
        #[MapQueryParameter(
            filter: FILTER_VALIDATE_REGEXP,
            options: ['regexp' => '/^[1-9][0-9]*$/']
        )]
        int $page = 1
    ): Response {
        return $this->render(
            'crud/books/index.html.twig',
            [
                'books' => $books->findPage(max(1, $page))
            ]
        );
    }

    /** Переглянути книгу */
    #[Route('/books/{id}', name: 'app_crud_book_show', requirements: ['id' => '\d+'], methods: 'GET')]
    public function show(Book $book): Response
    {
        return $this->render('crud/books/show.html.twig', ['book' => $book]);
    }

    /** Видалити книгу */
    #[Route('/books/{id}/_delete')]
    public function delete(Book $book, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($book);
        $entityManager->flush();

        $this->addFlash('success', "Book {$book->getName()} deleted successfully");

        return $this->redirectToRoute('app_crud_book_index');
    }

    /** Створити книгу */
    #[Route('/books/new', name: 'app_crud_book_new')]
    public function new(): Response
    {
        return $this->render('crud/books/new.html.twig');
    }

    /** Редагувати книгу */
    #[Route('/books/{id}/_update', name: 'app_crud_book_update')]
    public function update(Book $book): Response
    {
        return $this->render('crud/books/edit.html.twig', ['book' => $book]);
    }
}