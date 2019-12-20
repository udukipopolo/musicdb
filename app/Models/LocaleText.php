<?php

namespace App\Models;

use App\Traits\AuthorObservable;
use App\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Model;

class LocaleText extends Model
{
    use AuthorObservable, PaginatorTrait;

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
