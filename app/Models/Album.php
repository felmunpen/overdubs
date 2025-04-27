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
 * @property string $name
 * @property int $artist_id
 * @property string|null $cover
 * @property int|null $release_year
 * @property int|null $average_rating
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \App\Models\Artist $artist
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Genre> $genres
 * @property-read int|null $genres_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Playlist> $lists
 * @property-read int|null $lists_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Song> $songs
 * @property-read int|null $songs_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Album newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Album newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Album query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Album whereArtistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Album whereAverageRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Album whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Album whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Album whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Album whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Album whereReleaseYear($value)
 * @mixin \Eloquent
 */
class Album extends Model
{
    //
    use HasFactory;

    protected $table = 'albums';

    protected $fillable = ['name', 'artist_id', 'cover', 'release_year', 'average_rating'];

    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }

    public function songs(): HasMany
    {
        return $this->hasMany(Song::class);
    }

    public function lists(): BelongsToMany
    {
        return $this->belongsToMany(Playlist::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function genres(): HasMany
    {
        return $this->hasMany(Genre::class);
    }

}

