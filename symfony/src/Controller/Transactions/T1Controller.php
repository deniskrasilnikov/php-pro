<?php

declare(strict_types=1);

namespace App\Controller\Transactions;

use App\Module\Literato\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/transactions')]
class T1Controller extends AbstractController
{
    #[Route('/1')]
    public function firstTransaction(BookRepository $bookRepository, EntityManagerInterface $entityManager): Response
    {
        $entityManager->beginTransaction();

        try {
            $book = $bookRepository->find(398);
            $book->setName($book->getName() . ' T1');
            $entityManager->flush();

            $entityManager->commit();
        } catch (\Exception) {
            $entityManager->rollback();
        }

        return new Response();
    }
}