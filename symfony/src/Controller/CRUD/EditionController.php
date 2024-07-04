<?php

declare(strict_types=1);

namespace App\Controller\CRUD;

use Literato\Repository\EditionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/editions')]
class EditionController extends AbstractController
{
    /** Переглянути усі видання (з пагінацією) */
    #[Route('/', name: 'app_crud_edition_index', methods: 'GET')]
    public function index(
        EditionRepository $editions,
        #[MapQueryParameter(
            filter: FILTER_VALIDATE_REGEXP,
            options: ['regexp' => '/^[1-9][0-9]*$/']
        )]
        int $page = 1
    ): Response {
        return $this->render(
            'crud/editions/index.html.twig',
            [
                'editions' => $editions->findPaginated(max(1, $page))
            ]
        );
    }
}