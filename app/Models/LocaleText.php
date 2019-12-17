<?php

namespace App\Models;

use App\Traits\AuthorObservable;
use Illuminate\Database\Eloquent\Model;

class LocaleText extends Model
{
    use AuthorObservable;

    protected $fillable = [
        'locale',
        'column',
        'text',
    ];

    public function localable()
    {
        return $this->morphTo();
    }
}
