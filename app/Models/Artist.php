<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Artist extends Model
{
    //
    use HasFactory;

    protected $table = 'artists';

    protected $fillable = ['artist_id', 'artist_name', 'registered', 'artist_cover', 'user_id'];

    public function albums(): HasMany
    {
        return $this->hasMany(Album::class);
    }
}
