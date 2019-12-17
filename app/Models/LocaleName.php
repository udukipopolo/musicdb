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
        'name',
    ];

    public function localable()
    {
        return $this->morphTo();
    }
}