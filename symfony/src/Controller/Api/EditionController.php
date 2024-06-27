<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Doctrine\ORM\EntityManagerInterface;
use Literato\DTO\CreateEdition;
use Literato\DTO\UpdateEdition;
use Literato\Entity\Edition;
use Literato\Manager\EditionManager;
use Literato\Repository\EditionRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/editions')]
class EditionController extends AbstractController
{
    #[Route('/', methods: ['GET'], format: 'json')]
    #[Cache(maxage: 60, public: true, mustRevalidate: true)] # застосовуємо HTTP кешування на 60 секунд
    public function list(
        EditionRepository $editionRepository,
        SerializerInterface $serializer,
        #[MapQueryParameter] int $page = 1
    ): Response {
        return new Response(
            $serializer->serialize(
                $editionRepository->findPaginated(max(1, $page)),
                'json',
                [
                    'groups' => ['edition_list', 'book_item']
                ]
            )
        );
    }

    #[Route('/{id}', methods: ['GET'], format: 'json')]
    public function get(Edition $edition, SerializerInterface $serializer): Response
    {
        return new Response(
            $serializer->serialize(
                $edition,
                'json',
                [
                    'groups' => ['edition_item', 'book_item', 'author_item', 'author_books']
                ]
            )
        );
    }

    #[Route('', methods: ['POST'], format: 'json')]
    public function create(
        #[MapRequestPayload] CreateEdition $createEdition,
        EditionManager $editionManager,
        SerializerInterface $serializer
    ): Response {
        return new Response(
            $serializer->serialize(
                $editionManager->createEdition($createEdition),
                'json',
                [
                    'groups' => ['edition_item', 'book_item', 'author_item']
                ]
            ),
            Response::HTTP_CREATED
        );
    }

    #[Route('/{id}', methods: ['PATCH'], format: 'json')]
    public function update(
        Edition $edition,
        #[MapRequestPayload] UpdateEdition $updateEdition,
        EditionManager $editionManager,
        SerializerInterface $serializer
    ): Response {
        return new Response(
            $serializer->serialize(
                $editionManager->updateEdition($updateEdition, $edition),
                'json',
                [
                    'groups' => ['edition_item', 'book_item', 'author_item']
                ]
            )
        );
    }

    #[Route('/{id}', methods: ['DELETE'], format: 'json')]
    public function delete(Edition $edition, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        try {
            $entityManager->remove($edition);
            $entityManager->flush();
        } catch (\Exception $e) {
            $logger->error($e->getMessage());
            return new Response('Error on deleting edition', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}