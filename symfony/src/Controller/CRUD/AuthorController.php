<?php

declare(strict_types=1);

namespace App\Controller\CRUD;

use Literato\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/authors')]
class AuthorController extends AbstractController
{
    #[Route('/', name: 'app_crud_author_index', methods: 'GET')]
    public function index(): Response
    {
        return $this->render('crud/authors/index.html.twig');
    }

    #[Route('.json', name: 'app_crud_author_page_json', methods: 'GET')]
    public function pageJson(
        AuthorRepository $authorRepository,
        SerializerInterface $serializer,
        #[MapQueryParameter(
            filter: FILTER_VALIDATE_REGEXP,
            options: ['regexp' => '/^[1-9][0-9]*$/']
        )]
        int $page = 1
    ): Response {
        return new Response(
            $serializer->serialize($authorRepository->findPage($page), 'json', ['groups' => 'author_list'])
        );
    }

    #[Route('/page', name: 'app_crud_author_page', methods: 'GET')]
    public function page(
        AuthorRepository $authorRepository,
        #[MapQueryParameter(
            filter: FILTER_VALIDATE_REGEXP,
            options: ['regexp' => '/^[1-9][0-9]*$/']
        )]
        int $page = 1
    ): Response {
        return $this->render('crud/authors/_page.html.twig', [
            'authors' => $authorRepository->findPage($page)
        ]);
    }


}