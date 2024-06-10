<?php

declare(strict_types=1);

namespace App\Controller;

use Literato\Repository\EditionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    /** Початкова сторінка проєкту */
    #[Route('/')]
    public function index(EditionRepository $editionRepository): Response
    {
        return $this->render('index.html.twig', [
            'bestSellers' => $editionRepository->findBestSellers(3)
        ]);
    }
}