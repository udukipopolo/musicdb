<?php

namespace App\Models;

use App\Traits\AuthorObservable;
use App\Traits\PaginatorTrait;
use Illuminate\Database\Eloquent\Model;

class LocaleName extends Model
{
    use AuthorObservable, PaginatorTrait;

    protected $fillable = [
        'locale',
        'column',
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
