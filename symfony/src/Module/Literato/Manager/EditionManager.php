<?php

declare(strict_types=1);

namespace App\Module\Literato\Manager;

use App\Module\Literato\DTO\CreateEdition;
use App\Module\Literato\DTO\UpdateEdition;
use App\Module\Literato\Entity\Edition;
use App\Module\Literato\Entity\Publisher;
use App\Module\Literato\Event\EditionPublishedEvent;
use App\Module\Literato\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

readonly class EditionManager
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private BookRepository $bookRepository,
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function createEdition(CreateEdition $createEdition): Edition
    {
        $book = $this->bookRepository->find($createEdition->bookId);

        if (!$book) {
            throw new EntityNotFoundException('Book is not found.');
        }

        $publisher = $this->entityManager->getRepository(Publisher::class)->find($createEdition->publisherId);

        if (!$publisher) {
            throw new EntityNotFoundException('Publisher is not found.');
        }

        $edition = new Edition(
            book: $book,
            publisher: $publisher,
            price: $createEdition->price,
            authorBaseReward: $createEdition->authorBaseReward,
        );

        $this->entityManager->persist($edition);
        $this->entityManager->flush();

        return $edition;
    }

    public function updateEdition(UpdateEdition $updateEdition, Edition $edition): Edition
    {
        if ($updateEdition->status !== null) {
            $edition->setStatus($updateEdition->status);
        }

        if ($updateEdition->authorBaseReward !== null) {
            $edition->setAuthorBaseReward($updateEdition->authorBaseReward);
        }

        if ($updateEdition->authorRewardPerCopy !== null) {
            $edition->setAuthorRewardPerCopy($updateEdition->authorRewardPerCopy);
        }

        if ($updateEdition->soldCopiesCount !== null) {
            $edition->setSoldCopiesCount($updateEdition->soldCopiesCount);
        }

        if ($updateEdition->price !== null) {
            $edition->setPrice($updateEdition->price);
        }

        $this->entityManager->flush();

        return $edition;
    }

    public function publish(Edition $edition): void
    {
        $edition->publish();
        $this->entityManager->flush();
        $this->eventDispatcher->dispatch(new EditionPublishedEvent($edition));
    }
}