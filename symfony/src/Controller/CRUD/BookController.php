<?php

declare(strict_types=1);

namespace App\Controller\CRUD;

use App\Form\BookType as BookForm;
use App\Module\Literato\Entity\Book;
use App\Module\Literato\Entity\Enum\BookType;
use App\Module\Literato\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Contracts\Translation\TranslatorInterface;

use const FILTER_VALIDATE_REGEXP;

/** CRUD-контролер для книг */
#[Route('/books')]
class BookController extends AbstractController
{
    /** Переглянути усі книги (з пагінацією) */
    #[Route('/', name: 'app_crud_book_index', methods: 'GET')]
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
    #[Route('/{id}', name: 'app_crud_book_show', requirements: ['id' => Requirement::POSITIVE_INT], methods: 'GET')]
    public function show(Book $book): Response
    {
        return $this->render('crud/books/show.html.twig', ['book' => $book]);
    }

    /** Видалити книгу */
    #[Route('/{id}/_delete', requirements: ['id' => Requirement::POSITIVE_INT])]
    public function delete(Book $book, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($book);
        $entityManager->flush();

        $this->addFlash('success', "Book {$book->getName()} deleted successfully");

        return $this->redirectToRoute('app_crud_book_index', [], Response::HTTP_SEE_OTHER);
    }

    /** Створити книгу */
    #[Route('/new/{bookType}', name: 'app_crud_book_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, BookType $bookType): Response {

        $form = $this->createForm(BookForm::class, $bookType->entity());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($book = $form->getData());
            $entityManager->flush();

            $this->addFlash('success', "Book {$book->getName()} created successfully");

            return $this->redirectToRoute('app_crud_book_show', ['id' => $book->getId()]);
        }

        return $this->render('crud/books/new.html.twig', [
            'form' => $form
        ]);
    }

    /** Редагувати книгу */
    #[Route('/{id}/_update', name: 'app_crud_book_update', requirements: ['id' => Requirement::POSITIVE_INT],
        methods: ['GET', 'POST'])]
    public function update(Book $book, Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(BookForm::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash(
                'success',
                $translator->trans(
                    'flashes.book_updated_successfully',
                    ['%book_name%' => $book->getName($request->getLocale())]
                )
            );
            return $this->redirectToRoute('app_crud_book_show', ['id' => $book->getId()]);
        }

        return $this->render('crud/books/edit.html.twig', ['book' => $book, 'form' => $form]);
    }
}