<?php

namespace App\Models;

use App\Traits\AuthorObservable;
use App\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    use AuthorObservable, PaginatorTrait;

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
