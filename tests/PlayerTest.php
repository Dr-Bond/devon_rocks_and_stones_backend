<?php

namespace App\Tests;

use App\Entity\Player;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase
{
    private $user;
    private $firstName;
    private $surname;
    private $dateOfBirth;
    private $addressLineOne;
    private $city;
    private $county;
    private $postcode;

    public function setUp()
    {
        $this->user = $this->createUser();
        $this->firstName = 'firstName';
        $this->surname = 'surname';
        $this->dateOfBirth = new \DateTime();
        $this->addressLineOne = 'addressLineOne';
        $this->city = 'city';
        $this->county = 'county';
        $this->postcode = 'postcode';
    }

    private function createUser()
    {
        return new User('test@email.com');
    }

    private function createPlayer()
    {
        return new Player($this->user, $this->firstName, $this->surname, $this->dateOfBirth, $this->addressLineOne, $this->city, $this->county, $this->postcode);
    }

    public function testConstructor()
    {
        $user = $this->createPlayer();
        $this->assertSame($this->user, $user->getUser());
        $this->assertSame($this->firstName, $user->getFirstName());
        $this->assertSame($this->surname, $user->getSurname());
        $this->assertInstanceOf(\DateTime::class,$user->getDateOfBirth());
        $this->assertSame($this->addressLineOne, $user->getAddressLineOne());
        $this->assertSame($this->city, $user->getCity());
        $this->assertSame($this->county, $user->getCounty());
        $this->assertSame($this->postcode, $user->getPostcode());
    }

    public function testSetFirstName()
    {
        $string = 'firstNameSet';
        $player = $this->createPlayer();
        $player->setFirstName($string);
        $this->assertEquals($player->getFirstName(), $string);
    }

    public function testSetSurname()
    {
        $string = 'surnameSet';
        $player = $this->createPlayer();
        $player->setSurname($string);
        $this->assertEquals($player->getSurname(), $string);
    }

    public function testSetDateOfBirth()
    {
        $player = $this->createPlayer();
        $player->setDateOfBirth(new \DateTime());
        $this->assertInstanceOf(\DateTime::class, $player->getDateOfBirth());
    }

    public function testSetAddressLineOne()
    {
        $string = 'test address line';
        $player = $this->createPlayer();
        $player->setAddressLineOne($string);
        $this->assertEquals($player->getAddressLineOne(), $string);
    }

    public function testSetAddressLineTwo()
    {
        $string = 'test address line';
        $player = $this->createPlayer();
        $player->setAddressLineTwo($string);
        $this->assertEquals($player->getAddressLineTwo(), $string);
    }

    public function testSetCity()
    {
        $string = 'test city';
        $player = $this->createPlayer();
        $player->setCity($string);
        $this->assertEquals($player->getCity(), $string);
    }

    public function testSetCounty()
    {
        $string = 'test county';
        $player = $this->createPlayer();
        $player->setCounty($string);
        $this->assertEquals($player->getCounty(), $string);
    }

    public function testSetPostcode()
    {
        $string = 'test postcode';
        $player = $this->createPlayer();
        $player->setPostcode($string);
        $this->assertEquals($player->getPostcode(), $string);
    }
}