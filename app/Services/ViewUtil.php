<?php
namespace App\Services;

class ViewUtil
{
    public static function hasErrorClass($errors, $key)
    {
        return $errors->has($key) ? ' is-invalid' : '';
    }

    public static function myLocaleUrl($locale)
    {
        // URLをパースする
        $urlParsed = parse_url(\Request::fullUrl());
        if (isset($urlParsed['query'])) {
            parse_str($urlParsed['query'], $params);
        }
        // GETパラメータのlangnの値に引数を格納する
        $params['lang'] = $locale;
        // クエリ部分を整形する
        $paramsJoined = [];
        foreach($params as $param => $value) {
           $paramsJoined[] = "$param=$value";
        }
        $query = implode('&', $paramsJoined);
        // URL全体を整形する
        $url = (App::environment('production') ? 'https' : $urlParsed['scheme']).'://'.
               $urlParsed['host']. // user と pass は扱わない
               (isset($urlParsed['port']) ? ':'.$urlParsed['port'] : '').
               (isset($urlParsed['path']) ? $urlParsed['path'] : '/').
               '?'.$query.
               (isset($urlParsed['fragment']) ? '#'.$urlParsed['fragment'] : '');
        return $url;
    }
}
