<?php

namespace App\CommandBus\Api;

use App\Model\User;
use App\Model\Player;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class CreateUserCommand
{
    private $email;
    private $password;
    private $firstName;
    private $surname;
    private $dateOfBirth;
    private $addressLineOne;
    private $addressLineTwo;
    private $city;
    private $county;
    private $postcode;

    public function __construct(User $user, Player $player)
    {
        $this->email = $user->getEmail();
        $this->password = $user->getPassword();
        $this->firstName = $player->getFirstName();
        $this->surname = $player->getSurname();
        $this->dateOfBirth = $player->getDateOfBirth();
        $this->addressLineOne = $player->getAddressLineOne();
        $this->addressLineTwo = $player->getAddressLineTwo();
        $this->city = $player->getCity();
        $this->county = $player->getCounty();
        $this->postcode = $player->getPostcode();
    }

    public function getEmail():? string
    {
        return $this->email;
    }

    public function getPassword():? string
    {
        return $this->password;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function getAddressLineOne(): ?string
    {
        return $this->addressLineOne;
    }

    public function getAddressLineTwo(): ?string
    {
        return $this->addressLineTwo;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getCounty(): ?string
    {
        return $this->county;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('email', new Assert\NotBlank());
        $metadata->addPropertyConstraint('password', new Assert\NotBlank());
        $metadata->addPropertyConstraint('firstName', new Assert\NotBlank());
        $metadata->addPropertyConstraint('surname', new Assert\NotBlank());
        $metadata->addPropertyConstraint('dateOfBirth', new Assert\NotBlank());
        $metadata->addPropertyConstraint('addressLineOne', new Assert\NotBlank());
        $metadata->addPropertyConstraint('city', new Assert\NotBlank());
        $metadata->addPropertyConstraint('county', new Assert\NotBlank());
        $metadata->addPropertyConstraint('postcode', new Assert\NotBlank());

        $metadata->addPropertyConstraint('email', new Assert\Email());
    }
}