<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
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
}
