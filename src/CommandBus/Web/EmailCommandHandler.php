<?php

namespace App\CommandBus\Web;

use App\Entity\Location;
use App\Helper\Orm;
use App\Provider\GoogleProviderInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Dotenv\Dotenv;

class EmailCommandHandler
{
    const DISTANCE_10KM = 10000;

    private $orm;
    private $googleProvider;
    private $senderEmail;
    private $mailer;
    private $twig;
    protected static $defaultName = 'app:inactive-players-email';

    public function __construct(Orm $orm, GoogleProviderInterface $googleProvider, Dotenv $dotenv, \Swift_Mailer $mailer, ContainerInterface $container)
    {
        $dotenv->load(__DIR__.'/../../../.env');
        $this->orm = $orm;
        $this->googleProvider = $googleProvider;
        $this->senderEmail = $_ENV['EMAIL'];
        $this->mailer = $mailer;
        $this->twig = $container->get('twig');
    }
    public function __invoke(EmailCommand $command)
    {
        $orm = $this->orm;
        $player = $command->getPlayer();
        $locations = $orm->getRepository(Location::class)->findFoundStonesByLocation();

        $stones = [];
        foreach ($locations as $location) {
            $distance = $this->googleProvider->getDistance($player->getCity(), $location['area']);
            if ($distance < self::DISTANCE_10KM and $distance !== null) {
                $stones[] = $location;
            }
        }
        $user = $player->getUser();
        $mapUrl = $this->googleProvider->getStaticMap($player->getCity(),$stones);
        $message = (new \Swift_Message())
            ->setFrom($this->senderEmail)
            ->setTo($user->getEmail())
            ->setSubject('Rocks And Stones In Your Location')
            ->setContentType("text/html")
            ->setBody(
                $this->twig->render(
                    'messenger/send_email.html.twig',
                    ['player' => $player, 'mapUrl' => $mapUrl,'content' => $command->getContent()]
                )
            )
        ;
        $this->mailer->send($message);
    }
}