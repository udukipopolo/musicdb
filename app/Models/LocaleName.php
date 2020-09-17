<?php

namespace App\Models;

use App\Traits\AuthorObservable;
use Illuminate\Database\Eloquent\Model;

class LocaleName extends Model
{
    use AuthorObservable;

    protected $fillable = [
        'locale',
        'column',
        'artist_id',
        'name',
    ];

    public function localable()
    {
        return $this->morphTo();
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }
}
