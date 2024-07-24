<?php

namespace App\Module\Literato\Entity;

use App\Module\Literato\Entity\Enum\EditionStatus;
use App\Module\Literato\Repository\EditionRepository;
use App\Module\Literato\Service\Printing\PrintableInterface;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\{Column, Entity, GeneratedValue, Id, JoinColumn, ManyToOne, Table};
use Symfony\Component\Serializer\Attribute as Serialize;

/**
 * Book edition by concrete publisher
 */
#[Entity(repositoryClass: EditionRepository::class)]
#[Table(name: 'edition')]
class Edition implements PrintableInterface
{
    use PublisherAware;
    use Timestampable;

    #[Id]
    #[GeneratedValue]
    #[Column(type: Types::INTEGER)]
    #[Serialize\Groups(['edition_list', 'edition_item'])]
    private int $id;

    public function __construct(
        #[ManyToOne(targetEntity: Book::class)]
        #[JoinColumn(name: 'book_id', referencedColumnName: 'id')]
        #[Serialize\Groups(['edition_list', 'edition_item'])]
        #[Serialize\MaxDepth(1)]
        private readonly Book $book,

        #[ManyToOne(targetEntity: Publisher::class)]
        #[JoinColumn(name: 'publisher_id', referencedColumnName: 'id')]
        protected Publisher $publisher,

        #[Column(name: 'published_at', type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
        #[Serialize\Groups(['edition_item'])]
        private ?DateTime $publishedAt = null,

        #[Column(type: 'smallint')]
        #[Serialize\Groups(['edition_list'])]
        private int $price = 0,

        #[Column(type: 'smallint')]
        private readonly int $authorPenalty = 0,

        #[Column(name: 'author_reward_base', type: 'smallint')]
        #[Serialize\Groups(['edition_list'])]
        private int $authorBaseReward = 0,

        #[Column(name: 'author_reward_copy', type: 'smallint')]
        private int $authorRewardPerCopy = 0,

        #[Column(name: 'sold_copies_count', type: 'integer')]
        private int $soldCopiesCount = 0,

        #[Column(length: 15)]
        #[Serialize\Groups(['edition_item'])]
        private EditionStatus $status = EditionStatus::InProgress,
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
     * @return EditionStatus
     */
    public function getStatus(): EditionStatus
    {
        return $this->status;
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

    public function getPrintData(): string
    {
        return implode("\n", $this->getFullInfo());
    }

    public function getBook(): Book
    {
        return $this->book;
    }

    public function getName(): string
    {
        return $this->book->getName();
    }

    public function getAuthorName(): string
    {
        return $this->book->getAuthor()->getFullName();
    }

    public function getPublisherName(): string
    {
        return $this->publisher->getName();
    }

    public function getIsbn10(): string
    {
        return $this->book->getIsbn10();
    }

    /**
     * @param int $authorBaseReward
     */
    public function setAuthorBaseReward(int $authorBaseReward): void
    {
        $this->authorBaseReward = $authorBaseReward;
    }

    /**
     * @param int $authorRewardPerCopy
     */
    public function setAuthorRewardPerCopy(int $authorRewardPerCopy): void
    {
        $this->authorRewardPerCopy = $authorRewardPerCopy;
    }

    /**
     * @param int $soldCopiesCount
     */
    public function setSoldCopiesCount(int $soldCopiesCount): void
    {
        $this->soldCopiesCount = $soldCopiesCount;
    }

    /**
     * @param EditionStatus $status
     */
    public function setStatus(EditionStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * @param int $price
     */
    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    public function publish()
    {
        if (!$this->price) {
            $this->price = rand(1000, 5000);
        }

        if (!$this->authorBaseReward) {
            $this->authorBaseReward = min($this->book->getAuthor()->getBooksCount() * 100, 1000);
        }

        $this->publishedAt = new DateTime();
        $this->status = EditionStatus::Published;
    }

    public function isPublished(): bool
    {
        return $this->status == EditionStatus::Published;
    }

    public function getPublisher(): Publisher
    {
        return $this->publisher;
    }
}