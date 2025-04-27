<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property int|null $registered
 * @property string|null $artist_pic
 * @property int|null $user_id
 * @property string|null $description
 * @property string|null $info
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Album> $albums
 * @property-read int|null $albums_count
 * @method static \Database\Factories\ArtistFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artist query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artist whereArtistPic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artist whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artist whereInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artist whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artist whereRegistered($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Artist whereUserId($value)
 * @mixin \Eloquent
 */
class Artist extends Model
{
    //
    use HasFactory;

    protected $table = 'artists';

    protected $fillable = ['name', 'artist_pic', 'description', 'info'];

    public function albums(): HasMany
    {
        return $this->hasMany(Album::class);
    }

}
