<?php
namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class AuthorObserver
{
    public function creating(Model $model)
    {
        if (\Auth::check()) {
            $user_id = \Auth::user()->id;
        } else {
            $user_id = null;
        }
        $model->created_by = $user_id;
    }

    public function updating(Model $model)
    {
        if (\Auth::check()) {
            $user_id = \Auth::user()->id;
        } else {
            $user_id = null;
        }
        $model->updated_by = $user_id;
    }

    public function saving(Model $model)
    {
        if (\Auth::check()) {
            $user_id = \Auth::user()->id;
        } else {
            $user_id = null;
        }
        $model->updated_by = $user_id;
    }
}
