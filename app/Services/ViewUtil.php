<?php
namespace App\Services;

class ViewUtil
{
    public static function hasErrorClass($errors, $key)
    {
        return $errors->has($key) ? ' is-invalid' : '';
    }
}
