<?php
// src/Controller/WeatherController.php

namespace App\Controller;

use App\Service\OpenWeatherMapService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    
    private $weatherService;

    public function __construct(OpenWeatherMapService $weatherService)
    {
        $this->weatherService = $weatherService;
    }
    #[Route('/weather',name:'app_weather')]
    public function index()
    {
        $city = 'Gouvernorat de Tunis'; 
        $weatherData = $this->weatherService->getWeather($city);

        // Handle weather data as per your application's requirements

        //return new Response('Weather Data: ' . print_r($weatherData, true));
        $placeName = $weatherData['name'];
        $latitude = $weatherData['coord']['lat'];
        $longitude = $weatherData['coord']['lon'];
        $currentTempKelvin = $weatherData['main']['temp'];
        $cloudsDescription = $weatherData['weather'][0]['description'];
        $humidity = $weatherData['main']['humidity'];

        $currentTempCelsius = $currentTempKelvin - 273.15;

        $formattedWeatherData = "Place Name: $placeName\n";
        $formattedWeatherData .= "Latitude: $latitude\n";
        $formattedWeatherData .= "Longitude: $longitude\n";
        $formattedWeatherData .= "Clouds Description: $cloudsDescription\n";
        $formattedWeatherData .= "Humidity: $humidity%\n";
        $formattedWeatherData .= "Current Temperature: $currentTempCelsius °C";

        //return new Response($formattedWeatherData);
        return $this->render('weather.html.twig', [
            'placeName' => $placeName,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'cloudsDescription' => $cloudsDescription,
            'humidity' => $humidity,
            'currentTemp' => $currentTempCelsius,
      ]);
    }
}
