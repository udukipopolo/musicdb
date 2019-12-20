<?php

namespace App\Models;

use App\Traits\AuthorObservable;
use App\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use AuthorObservable, PaginatorTrait;

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

    public function locale_name()
    {
        return $this->morphMany('App\Models\LocaleName', 'localable');
    }

    public function locale_text()
    {
        return $this->morphMany('App\Models\LocaleText', 'localable');
    }

    public function part_artist_name()
    {
        return $this->morphMany('App\Models\LocaleName', 'localable')->where('column', 'artist_name');
    }

    public function part_name()
    {
        return $this->morphMany('App\Models\LocaleName', 'localable')->where('column', 'name');
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
