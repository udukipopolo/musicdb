<?php
namespace App\Services;

class ViewUtil
{
    public static function hasErrorClass($errors, $key)
    {
        \Log::debug('hasErrorClass:'.$key.':'.$errors->has($key));

        return $errors->has($key) ? ' is-invalid' : '';
    }
}
