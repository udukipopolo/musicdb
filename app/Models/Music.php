<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    protected $fillable = [
        'title',
        'album_id',
        'track_no',
    ];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function parts()
    {
        return $this->hasMany(Part::class);
    }
}
