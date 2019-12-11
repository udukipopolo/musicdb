<?php
namespace App\Traits;

use App\Models\User;
use App\Observers\AuthorObserver;
use Illuminate\Database\Eloquent\Model;

trait AuthorObservable
{
    public static function bootAuthorObservable()
    {
        self::observe(AuthorObserver::class);
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updated_by()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
