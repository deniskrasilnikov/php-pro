<?php

namespace Literato\Entity;

use DateTime;
use Literato\Entity\Enum\EditionStatus;

/**
 * Book edition by concrete publisher
 */
class Edition
{
    use Identity;
    use PublisherAware;

    public function __construct(
        private readonly Book $book,
        protected Publisher $publisher,
        private readonly ?DateTime $publishedAt = null,
        private readonly float $price = 0.0,
        private readonly float $authorBaseReward = 0,
        private readonly float $authorRewardPerCopy = 0,
        private readonly int $soldCopiesCount = 0,
        private readonly EditionStatus $status = EditionStatus::InProgress,
    ) {
    }

    public function getAuthorReward(): float
    {
        return $this->authorBaseReward + $this->authorRewardPerCopy * $this->soldCopiesCount;
    }

    /**
     * Get edition data info as array of name => value
     * @return array
     */
    public function getFullInfo(): array
    {
        return [
            'Book' => $this->book->getName(),
            'ISBN' => $this->book->getIsbn10(),
            'Publisher' => $this->publisher->getName(),
            'Status' => $this->status->value,
            'Price' => $this->price,
            'SoldCount' => $this->soldCopiesCount,
            'AuthorReward' => $this->getAuthorReward(),
        ];
    }

    /**
     * @return Book
     */
    public function getBook(): Book
    {
        return $this->book;
    }

    /**
     * @return Publisher
     */
    public function getPublisher(): Publisher
    {
        return $this->publisher;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getSoldCopiesCount(): int
    {
        return $this->soldCopiesCount;
    }

    /**
     * @return EditionStatus
     */
    public function getStatus(): EditionStatus
    {
        return $this->status;
    }

    /**
     * @return float
     */
    public function getAuthorRewardPerCopy(): float
    {
        return $this->authorRewardPerCopy;
    }
}