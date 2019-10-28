<?php

namespace App\Provider;

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GoogleProvider implements GoogleProviderInterface
{
    private $httpClient;
    private $apiKey;

    public function __construct(HttpClient $httpClient, Dotenv $dotenv)
    {

        $dotenv->load(__DIR__.'/../../.env');
        $this->httpClient = $httpClient;
        $this->apiKey = $_ENV['GOOGLE_API_KEY'];
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function getDistance($locationA, $locationB)
    {
        $httpClient = $this->httpClient->create();
        $locationA = urlencode($locationA);
        $locationB = urlencode($locationB);

        try {
            $response = $httpClient->request('GET','https://maps.googleapis.com/maps/api/distancematrix/json?origins='.$locationA.',UK&destinations='.$locationB.',UK&key='.$this->apiKey);
            $distance = $response->toArray();
        } catch (TransportExceptionInterface $e) {
            throw new \Exception('Error: '.$e->getMessage());
        }

        if(isset($distance['rows'])) {
            if(count($distance['rows']) > 0) {
                if(count($distance['rows'][0]['elements']) > 0) {
                    if(isset($distance['rows'][0]['elements'][0]['distance'])) {
                        $distance = $distance['rows'][0]['elements'][0]['distance']['value'];
                        return $distance;
                    }
                }
            }
        }
        return null;
    }

    public function getStaticMap($mainLocation, $locations = [])
    {
        $mainLocation = urlencode($mainLocation);
        $markers ='';
        foreach($locations as $location) {
            $coordinates = $this->getLongLat($location['area']);
            $markers .= '&markers=color:blue%7Clabel:'.$location['stoneCount'].'%7C'.$coordinates['lat'].','.$coordinates['lng'];
        }
        $mapUrl = 'https://maps.googleapis.com/maps/api/staticmap?center='.$mainLocation.',UK&zoom=10&size=600x300&maptype=roadmap'.$markers.'&key='.$this->apiKey;
        return $mapUrl;
    }

    public function getLongLat($location)
    {
        $httpClient = $this->httpClient->create();
        $location = urlencode($location);

        try {
            $response = $httpClient->request('GET','https://maps.googleapis.com/maps/api/geocode/json?address='.$location.',Devon&key='.$this->apiKey);
            $longLat = $response->toArray();
        } catch (TransportExceptionInterface $e) {
            throw new \Exception('Error: '.$e->getMessage());
        }

        if(count($longLat['results']) > 0)
        {
            return $longLat['results'][0]['geometry']['location'];
        }

        return [];
    }

}