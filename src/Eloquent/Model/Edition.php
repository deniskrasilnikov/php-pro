<?php

namespace Eloquent\Model;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Literato\Entity\Enum\EditionStatus;

/**
 * Book edition by concrete publisher
 *
 * @property int $id
 * @property Book $book
 * @property Publisher $publisher
 * @property DateTime|null $published_at
 * @property int $price
 * @property int $author_reward_base
 * @property int $author_reward_copy
 * @property int $sold_copies_count
 * @property EditionStatus $status
 */
class Edition extends Model
{
    protected $table = 'edition';
    protected $primaryKey = 'id';

    protected $attributes = [
        'price' => 0,
        'author_reward_base' => 0,
        'author_reward_copy' => 0,
        'sold_copies_count' => 0,
        'status' => EditionStatus::InProgress->value,
    ];

    public function casts(): array
    {
        return [
            'status' => EditionStatus::class,
        ];
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class, 'publisher_id');
    }

    public function getAuthorReward(): float
    {
        return $this->author_reward_base + $this->author_reward_copy * $this->sold_copies_count;
    }

    /**
     * Get edition data info as array of name => value
     * @return array
     */
    public function getFullInfo(): array
    {
        return [
            'Book' => $this->book->name,
            'ISBN' => $this->book->isbn10,
            'Publisher' => $this->publisher->name,
            'Status' => $this->status->name,
            'Price' => $this->price / 100,
            'Sold Count' => $this->sold_copies_count,
            'Author Reward' => $this->getAuthorReward() / 100,
        ];
    }
}