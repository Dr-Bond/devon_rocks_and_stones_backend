<?php

namespace App\CommandBus\Api;

use App\Entity\Player;
use App\Entity\User;
use App\Helper\Orm;
use Psr\Container\ContainerInterface;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserCommandHandler
{
    private $orm;
    private $passwordEncoder;
    private $mailer;
    private $twig;

    public function __construct(Orm $orm, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer, ContainerInterface $container)
    {
        $this->orm = $orm;
        $this->passwordEncoder = $passwordEncoder;
        $this->mailer = $mailer;
        $this->twig = $container->get('twig');
    }

    public function __invoke(CreateUserCommand $command)
    {
        $orm = $this->orm;
        $user = new User(
            $command->getEmail()
        );
        $user->setPassword($this->encodePassword($user, $command->getPassword()));
        $orm->persist($user);

        $player = new Player(
            $user,
            $command->getFirstName(),
            $command->getSurname(),
            $command->getDateOfBirth(),
            $command->getAddressLineOne(),
            $command->getCity(),
            $command->getCounty(),
            $command->getPostcode()
        );
        $player->setAddressLineTwo($command->getAddressLineTwo());
        $orm->persist($player);
        $this->sendEmail($player);
        //$orm->flush();
    }

    private function encodePassword(User $user, string $password)
    {
        return $this->passwordEncoder->encodePassword(
            $user,
            $password
        );
    }

    private function sendEmail(Player $player)
    {
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__.'/../../../.env');
        $email = $_ENV['EMAIL'];
        $message = (new \Swift_Message())
            ->setFrom($email)
            ->setTo($player->getUser()->getEmail())
            ->setSubject('Welcome to Devon Rocks and Stones')
            ->setBody(
                $this->twig->render(
                    'messenger/register.html.twig',
                    ['player' => $player]
                )
            )
        ;
        $this->mailer->send($message);
    }

}