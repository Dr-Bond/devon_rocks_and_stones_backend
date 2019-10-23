<?php

namespace App\Command;

use App\Entity\Location;
use App\Entity\Player;
use App\Helper\Orm;
use App\Provider\GoogleProviderInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Dotenv\Dotenv;

class InactivePlayerEmailCommand extends Command
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
        $dotenv->load('.env');
        $this->orm = $orm;
        $this->googleProvider = $googleProvider;
        $this->senderEmail = $_ENV['EMAIL'];
        $this->mailer = $mailer;
        $this->twig = $container->get('twig');
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Inactive Players Email')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $orm = $this->orm;

        $io->title($this->getDescription());

        $players = $orm->getRepository(Player::class)->findInactivePlayers();

        if(count($players) < 1) {
            $io->success('No Inactive Players Found.');
            return;
        }

        $io->note(count($players).' Inactive Player(s) Found.');

        $locations = $orm->getRepository(Location::class)->findHiddenStonesLocations();

        $emails = [];

        foreach ($players as $player) {
            $stones = [];
            foreach ($locations as $location) {
                $distance = $this->googleProvider->getDistance($player->getCity(),$location['area']);
                if($distance < self::DISTANCE_10KM and $distance !== null) {
                    $stones[] = $location;
                }
            }
            $emails[] = [
                'player' => $player,
                'stones' => $stones
            ];
        }

        foreach($emails as $email) {
            $player = $email['player'];
            $stones = $email['stones'];
            $user = $player->getUser();
            $mapUrl = $this->googleProvider->getStaticMap($player->getCity(),$stones);
            $message = (new \Swift_Message())
                ->setFrom($this->senderEmail)
                ->setTo($user->getEmail())
                ->setSubject('Rocks And Stones In Your Location')
                ->setContentType("text/html")
                ->setBody(
                    $this->twig->render(
                        'messenger/inactive.html.twig',
                        ['player' => $player, 'mapUrl' => $mapUrl]
                    )
                )
            ;
            $this->mailer->send($message);
            $user->setInactiveEmailSentOn(new \DateTime());
            $orm->flush();
            $io->success('Email Sent: '.$user->getEmail());
        }
        $io->success('Finished');
    }
}
