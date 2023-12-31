<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

/**
 * App\Models\Author
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Author newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Author newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Author query()
 * @method static \Illuminate\Database\Eloquent\Builder|Author whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Author whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Author whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Author whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Author extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['name'];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'books_count' => $this->books()->count(),
        ];
    }
}
