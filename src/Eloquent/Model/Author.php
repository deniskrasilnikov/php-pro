<?php

declare(strict_types=1);

namespace Eloquent\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property Novel[] $novels
 * @property Novelette[] $novelettes
 */
class Author extends Model
{
    protected $table = 'author';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function novels(): HasMany
    {
        return $this->hasMany(Novel::class)->where(['type' => 'Novel']);
    }

    public function novelettes(): HasMany
    {
        return $this->hasMany(Novelette::class)->where(['type' => 'Novelette']);
    }

    public function getBooksCount(): int
    {
        return count($this->novels) + count($this->novelettes);
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}