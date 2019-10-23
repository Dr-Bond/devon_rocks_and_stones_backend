<?php

namespace App\Controller\Web;

use App\Controller\Controller;
use App\Entity\Location;

class LocationController extends Controller
{
    public function hiddenStones()
    {
        $results = $this->orm->getRepository(Location::class)->findHiddenStonesLocations();
        $data = "";
        foreach ($results as $result) {
            $stoneCount = $result['stoneCount'];
            $area = $result['area'];
            $data .= "['$area',$stoneCount],";
        }
        $data = "[".rtrim($data,',')."]";
        return $this->render('web/location/hidden_stones.html.twig',['data' => $data]);
    }

}
