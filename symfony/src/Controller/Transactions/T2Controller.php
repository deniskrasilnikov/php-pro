<?php

declare(strict_types=1);

namespace App\Controller\Transactions;

use App\Module\Literato\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/transactions')]
class T2Controller extends AbstractController
{
    #[Route('/2')]
    public function secondTransaction(BookRepository $bookRepository, EntityManagerInterface $entityManager): Response
    {
        $entityManager->wrapInTransaction(function () use ($bookRepository) {
            $book = $bookRepository->find(398);
            $book->setName($book->getName() . ' T2');
        });

        return new Response();
    }
}