<?php

declare(strict_types=1);

namespace Eloquent\Model;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Literato\Entity\Enum\Genre;

/**
 * @property int $id
 * @property string $name
 * @property string $isbn10
 * @property string $text
 * @property string $type
 * @property Author $author
 * @property Genre[] $genres
 */
class Book extends Model
{
    protected $table = 'book';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $attributes = [
        'genres' => Genre::NONE->value,
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    /**
     * Get book name, author name, ISBN and other info as array items (strings)
     * @return array
     */
    public function getFullInfo(): array
    {
        return [
            'Name' => $this->name,
            'Type' => $this->type,
            'Author' => $this->author->getFullName(),
            'ISBN' => $this->isbn10,
            'Genres' => implode(', ', array_column($this->genres, 'value')),
            'ShortText' => substr($this->text, 0, 50),
        ];
    }

    /**
     * Validate object data
     */
    public function validate(): void
    {
        $this->validateText($this->text);
    }

    protected function validateText(string $text): void
    {}

    /**
     * Cast $genres from enums to db value and back
     * @noinspection PhpUnused
     */
    protected function genres(): Attribute
    {
        return Attribute::make(
            get: fn(string $genres) => array_map(fn(string $value) => Genre::from($value), explode(',', $genres)),
            set: fn(array $genres) => implode(',', array_column($genres, 'value')),
        );
    }
}