<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = [
        'title',
        'artist_id',
        'artist_name',
        'description',
    ];

    public function musics() {
        return $this->hasMany(Music::class);
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }
}
