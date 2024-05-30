<?php

namespace App\Controller;

use Literato\Repository\EditionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EditionController extends AbstractController
{
    #[Route('/best-sellers/{count}', name: 'app_edition_best_sellers')]
    public function bestSellers(EditionRepository $editionRepository, Request $request, int $count = 3): Response
    {
        return new JsonResponse($editionRepository->findBestSellers($count, $request->query->get('publisher')));
    }
}
