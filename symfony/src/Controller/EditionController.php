<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Literato\Entity\Edition;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EditionController extends AbstractController
{
    #[Route('/best-sellers/{count}', name: 'app_edition_best_sellers')]
    public function bestSellers(EntityManagerInterface $entityManager, Request $request, int $count = 3): Response
    {
        $editionRepository = $entityManager->getRepository(Edition::class);

        return new JsonResponse($editionRepository->findBestSellers($count, $request->query->get('publisher')));
    }
}
