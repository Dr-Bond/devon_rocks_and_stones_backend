<?php

namespace App\Provider;

interface GoogleProviderInterface
{
    public function getDistance($locationA, $locationB);
    public function getStaticMap($mainLocation, $locations = []);
    public function getLongLat($location);
    public function getApiKey();
}