<?php

namespace App\Models;

use App\Traits\AuthorObservable;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use AuthorObservable;

    protected $fillable = [
        'music_id',
        'artist_id',
        'artist_name',
        'name',
    ];

    public function music()
    {
        return $this->belongsTo(Music::class);
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function part_artist_name()
    {
        return $this->morphMany('App\Models\LocaleName', 'localable')->where('column', 'artist_name');
    }

    public function part_name()
    {
        return $this->morphMany('App\Models\LocaleName', 'localable')->where('column', 'name');
    }
}
