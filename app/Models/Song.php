<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\BelongsToRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * 
 *
 * @property int $id
 * @property int $album_id
 * @property string|null $name
 * @property int|null $number
 * @property string|null $length
 * @property-read \App\Models\Album|null $artist
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereAlbumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereNumber($value)
 * @mixin \Eloquent
 */
class Song extends Model
{
    //
    use HasFactory;

    protected $table = 'songs';

    protected $fillable = ['name', 'number', 'length', 'release_year', 'average_rating'];

    public function artist(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }

}

