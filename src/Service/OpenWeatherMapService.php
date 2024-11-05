<?php

namespace App\Service;

use GuzzleHttp\Client;

class OpenWeatherMapService
{
    private $apiKey;
    private $client;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = new Client(['base_uri' => 'https://api.openweathermap.org/data/2.5/']);
    }

    public function getWeather($city)
    {
        $response = $this->client->get('weather', [
            'query' => [
                'q' => $city,
                'appid' => $this->apiKey,
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
