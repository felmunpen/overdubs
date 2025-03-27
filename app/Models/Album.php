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

class Album extends Model
{
    //
    use HasFactory;

    protected $table = 'albums';

    protected $fillable = ['album_id', 'album_name', 'artist_id', 'cover', 'release_date', 'genre'];

    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }
}

