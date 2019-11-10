<?php
namespace App\Services;

class ViewUtil
{
    public static function hasErrorClass($errors, $key)
    {
        \Log::debug('hasErrorClass:'.$key);
        return $errors->has($key) ? ' is-invalid' : '';
    }
}
