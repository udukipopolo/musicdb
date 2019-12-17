<?php

namespace App\Models;

use App\Traits\AuthorObservable;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use AuthorObservable;

    protected $fillable = [
        'name',
        'belonging',
    ];

    public function parts()
    {
        return $this->hasMany(Part::class);
    }

    public function albums()
    {
        return $this->hasMany(Album::class);
    }

    public function locale_name()
    {
        return $this->morphMany('App\Models\LocaleName', 'localable');
    }

    public function locale_text()
    {
        return $this->morphMany('App\Models\LocaleText', 'localable');
    }

    public function artist_name()
    {
        return $this->morphMany('App\Models\LocaleName', 'localable')->where('column', 'name');
    }

    public function artist_belonging()
    {
        return $this->morphMany('App\Models\LocaleText', 'localable')->where('column', 'belonging');
    }
}
