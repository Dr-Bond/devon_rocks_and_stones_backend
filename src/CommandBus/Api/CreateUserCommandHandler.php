<?php

namespace App\CommandBus\Api;

use App\Entity\Player;
use App\Entity\User;
use App\Helper\Orm;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserCommandHandler
{
    private $orm;
    private $passwordEncoder;

    public function __construct(Orm $orm, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->orm = $orm;
        $this->passwordEncoder = $passwordEncoder;
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

        $orm->flush();
    }

    private function encodePassword(User $user, string $password)
    {
        return $this->passwordEncoder->encodePassword(
            $user,
            $password
        );
    }

}