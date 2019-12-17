<?php

namespace App\Models;

use App\Traits\AuthorObservable;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    use AuthorObservable;

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

    public function locale_name()
    {
        return $this->morphMany('App\Models\LocaleName', 'localable');
    }

    public function locale_text()
    {
        return $this->morphMany('App\Models\LocaleText', 'localable');
    }

    public function music_title()
    {
        return $this->morphMany('App\Models\LocaleName', 'localable')->where('column', 'title');
    }
}
