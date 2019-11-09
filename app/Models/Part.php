<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $fillable = [
        'music_id',
        'artist_id',
        'artist_name',
        'part_name',
    ];

    public function music()
    {
        return $this->belongsTo(Music::class);
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }
}
