<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

/**
 * App\Models\Book
 *
 * @property int $id
 * @property int $author_id
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Book newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Book newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Book query()
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Book extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        "title",
        "author_id",
    ];

    protected static function booted(): void
    {
        static::created(function (Book $book) {
            $book->author->searchable();
        });

        // When an author is changed, make sure the search index is updated
        static::saved(function (Book $book) {
            $book->author->searchable();

            // If the author was changed, update the old author's search index
            if ($book->isDirty("author_id")) {
                Author::find($book->getOriginal("author_id"))?->searchable();
            }
        });

        // When a book is deleted, update the author's search index
        static::deleted(function (Book $book) {
            $book->author->searchable();
        });
    }


    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author_id' => $this->author_id,
            'author_name' => $this->author->name,
        ];
    }
}
