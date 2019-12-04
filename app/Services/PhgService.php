<?php
namespace App\Services;

use GuzzleHttp\Client;

class PhgService
{
    private $base_url =  "https://itunes.apple.com";

    public function __construct()
    {

    }

    public function getId($url)
    {
        $path = \parse_url($url, PHP_URL_PATH);
        $path = explode('/', $path);
        $id = end($path);

        return $id;
    }


    public function getLookup($query)
    {
        return $this->getData('/lookup', $query);
    }

    public function getSearch($query)
    {
        return $this->getData('/search', $query);
    }

    private function getData($path, $query)
    {
        $client = new Client([
            'base_uri' => $this->base_url,
        ]);
        $method = "GET";

        $query['country'] = "JP";

        $response = $client->get(
            $path,
            [
                'query' => $query,
            ]
        )->getBody()->getContents();

        \Log::debug($response);

        return $response;
    }
}
