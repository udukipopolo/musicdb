<?php

namespace App\Models;

use App\Traits\AuthorObservable;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use AuthorObservable;

    protected $fillable = [
        'title',
        'artist_id',
        'artist_name',
        'description',
        'affi_apple_music',
        'affi_amazon',
    ];

    public function musics() {
        return $this->hasMany(Music::class);
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function locale_name()
    {
        return $this->morphMany('App\Models\LocaleName', 'localable');
    }

    public function locale_text()
    {
        return $this->morphMany('App\Models\LocaleText', 'localable');
    }

    public function album_title()
    {
        return $this->morphMany('App\Models\LocaleName', 'localable')->where('column', 'title');
    }

    public function album_artist_name()
    {
        return $this->morphMany('App\Models\LocaleName', 'localable')->where('column', 'artist_name');
    }

    public function album_description()
    {
        return $this->morphMany('App\Models\LocaleText', 'localable')->where('column', 'description');
    }

    public function getLocaleName($column, $locale = null)
    {
        if (empty($locale)) {
            $locale = \App::getLocale();
        }

        if ($locale == 'ja') {
            return $this->locale_name()->where('column', $column)->where('locale', 'ja')->first()->name;
        } else {
            return $this->locale_name()
                ->where('column', $column)
                ->whereIn('locale', [$locale, 'ja'])
                ->orderByRaw("FIELD(locale, '{$locale}', 'ja')")
                ->first()->name;
        }
    }

    public function getLocaleText($column, $locale = null)
    {
        if (empty($locale)) {
            $locale = \App::getLocale();
        }

        if ($locale == 'ja') {
            return $this->locale_text()->where('column', $column)->where('locale', 'ja')->first()->text;
        } else {
            return $this->locale_text()
                ->where('column', $column)
                ->whereIn('locale', [$locale, 'ja'])
                ->orderByRaw("FIELD(locale, '{$locale}', 'ja')")
                ->first()->text;
        }
    }

}
