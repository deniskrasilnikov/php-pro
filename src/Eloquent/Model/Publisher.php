<?php

declare(strict_types=1);

namespace Eloquent\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $address
 * @property Author[] $authors
 */
class Publisher extends Model
{
    protected $table = 'publisher';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }
}