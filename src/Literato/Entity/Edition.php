<?php

namespace Literato\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\{Column, Entity, GeneratedValue, Id, JoinColumn, ManyToOne, Table};
use Literato\Entity\Enum\EditionStatus;

/**
 * Book edition by concrete publisher
 */
#[Entity]
#[Table(name: 'edition')]
class Edition
{
    use PublisherAware;
    use Timestampable;

    #[Id]
    #[GeneratedValue]
    #[Column(type: Types::INTEGER)]
    private int $id;

    public function __construct(
        #[ManyToOne(targetEntity: Book::class)]
        #[JoinColumn(name: 'book_id', referencedColumnName: 'id')]
        private readonly Book $book,

        #[ManyToOne(targetEntity: Publisher::class)]
        #[JoinColumn(name: 'publisher_id', referencedColumnName: 'id')]
        protected Publisher $publisher,

        #[Column(name: 'published_at', type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
        private readonly ?DateTime $publishedAt = null,

        #[Column(type: 'smallint')]
        private readonly int $price = 0,

        #[Column(name: 'author_reward_base', type: 'smallint')]
        private readonly int $authorBaseReward = 0,

        #[Column(name: 'author_reward_copy', type: 'smallint')]
        private readonly int $authorRewardPerCopy = 0,

        #[Column(name: 'sold_copies_count', type: 'integer')]
        private readonly int $soldCopiesCount = 0,

        #[Column(length: 15)]
        private readonly EditionStatus $status = EditionStatus::InProgress,
    ) {
        $this->initTimestampable();
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
            'Status' => $this->status->name,
            'Price' => $this->price / 100,
            'Sold Count' => $this->soldCopiesCount,
            'Author Reward' => $this->getAuthorReward() / 100,
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
     * @return int
     */
    public function getPrice(): int
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
     * @return int
     */
    public function getAuthorRewardPerCopy(): int
    {
        return $this->authorRewardPerCopy;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }
}