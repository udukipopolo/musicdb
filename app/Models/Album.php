<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = [
        'title',
    ];

    public function musics() {
        return $this->hasMany(Music::class);
    }
}
