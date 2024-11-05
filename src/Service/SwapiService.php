<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class SwapiService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getCharacter($id)
    {
        // Make an HTTP GET request to the SWAPI API to retrieve information about a character
        $response = $this->client->request('GET', 'https://swapi.dev/api/people/'.$id);

        // Check if the request was successful (status code 200)
        if ($response->getStatusCode() === 200) {
            // Decode the JSON response and return it as an associative array
            return $response->toArray();
        } else {
            // If the request was not successful, return null or handle the error as needed
            return null;
        }
    }

    
}
