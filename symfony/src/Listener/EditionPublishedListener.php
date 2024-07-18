<?php

declare(strict_types=1);

namespace App\Listener;

use App\Module\Literato\Event\EditionPublishedEvent;
use App\Module\Shop\Service\EditionService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: EditionPublishedEvent::class)]
readonly class EditionPublishedListener
{
    public function __construct(private EditionService $editionService)
    {

    }

    public function __invoke(EditionPublishedEvent $event): void
    {
        $edition = $event->getEdition();
        $this->editionService->createEdition(
            $edition->getName(),
            $edition->getIsbn10(),
            $edition->getAuthorName(),
            $edition->getPublisherName(),
            $edition->getPrice()
        );
    }
}