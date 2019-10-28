<?php

namespace App\Controller\Web;

use App\CommandBus\Web\EmailCommand;
use App\Controller\Controller;
use App\Entity\Player;
use App\Form\EmailFormType;
use App\Helper\Orm;
use App\Provider\GoogleProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

class PlayerController extends Controller
{
    private $provider;

    public function __construct(Security $security, UrlGeneratorInterface $urlGenerator, Orm $orm, GoogleProviderInterface $provider)
    {
        $this->provider = $provider;
        parent::__construct($security,$urlGenerator,$orm);
    }

    public function index()
    {
        $players = $this->orm->getRepository(Player::class)->findAll();
        return $this->render('web/player/index.html.twig',['players' => $players]);
    }

    public function active()
    {
        $results = $this->orm->getRepository(Player::class)->loginActivity();
        $headings = "'Weeks', ";
        $data = "'Last Active', ";
        foreach ($results as $result) {
            $heading = $result['number_of_weeks'];
            $users = $result['number_of_users'];
            $headings .= "'$heading',";
            $data .= "$users,";
        }
        $headings .= " { role: 'annotation' } ";
        $data = "[".$headings."],[".rtrim($data,',').",'']";
        return $this->render('web/player/active.html.twig',['data' => $data]);
    }

    public function weeklyPoints()
    {
        $points = $this->orm->getRepository(Player::class)->weeklyPoints();
        return $this->render('web/player/weekly_points.html.twig',['points' => $points]);
    }

    public function activity(Player $player)
    {
        $provider = $this->provider;
        $apiKey = $provider->getApiKey();
        $results = $this->orm->getRepository(Player::class)->activity($player);
        $data = "";
        $counter = 0;
        foreach ($results as $result) {
            $area = $result['area'];
            $activity = $result['activity'];
            $longLat = $provider->getLongLat($result['area']);
            $lat = $longLat['lat'];
            $long = $longLat['lng'];
            $data .= "['$area - $activity',$lat,$long,$counter],";
        }
        $data = "[".rtrim($data,',')."]";
        return $this->render('web/player/activity.html.twig',['data' => $data,'apiKey' => $apiKey, 'player' => $player]);
    }

    public function sendEmail(Player $player, Request $request, MessageBusInterface $bus)
    {
        $command = new EmailCommand($player);
        $form = $this->createForm(EmailFormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bus->dispatch($command);
            return $this->redirect($this->generateUrl('app_web_player'));
        } else {
            return $this->render('web/player/send_email.html.twig', array(
                'form' => $form->createView(),
                'player' => $player
            ));
        }
    }
}
