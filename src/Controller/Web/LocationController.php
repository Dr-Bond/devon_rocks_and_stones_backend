<?php

namespace App\Controller\Web;

use App\Controller\Controller;
use App\Entity\Location;
use App\Helper\Orm;
use App\Provider\GoogleProviderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

class LocationController extends Controller
{
    private $provider;

    public function __construct(Security $security, UrlGeneratorInterface $urlGenerator, Orm $orm, GoogleProviderInterface $provider)
    {
        $this->provider = $provider;
        parent::__construct($security,$urlGenerator,$orm);
    }

    public function hiddenStones()
    {
        $results = $this->orm->getRepository(Location::class)->findHiddenStonesByLocation();
        $data = "";
        foreach ($results as $result) {
            $stoneCount = $result['stoneCount'];
            $area = $result['area'];
            $data .= "['$area',$stoneCount],";
        }
        $data = "[".rtrim($data,',')."]";
        return $this->render('web/location/hidden_stones.html.twig',['data' => $data]);
    }

    public function foundStonesMap()
    {
        $provider = $this->provider;
        $results = $this->orm->getRepository(Location::class)->findFoundStonesByLocation();
        $data = "";
        $counter = 0;
        foreach ($results as $result) {
            $stoneCount = $result['stoneCount'];
            $area = $result['area'];
            $longLat = $provider->getLongLat($result['area']);
            $lat = $longLat['lat'];
            $long = $longLat['lng'];
            $data .= "['$area - $stoneCount Stone(s)',$lat,$long,$counter],";
        }
        $data = "[".rtrim($data,',')."]";
        return $this->render('web/location/found_stones_map.html.twig',['data' => $data, 'apiKey' => $provider->getApiKey()]);
    }

}
