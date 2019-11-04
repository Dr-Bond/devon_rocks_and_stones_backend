<?php

namespace App\Tests;

use App\Entity\Player;
use App\Entity\Stone;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class StoneTest extends TestCase
{
    private $owner;

    public function setUp()
    {
        $this->owner = $this->createPlayer();
    }

    private function createUser()
    {
        return new User('test@email.com');
    }

    private function createPlayer()
    {
        return new Player($this->createUser(), 'firstName', 'surname', new \DateTime(), 'addressLineOne', 'city', 'county', 'postcode');
    }

    private function createStone()
    {
        return new Stone($this->owner);
    }

    public function testConstructor()
    {
        $stone = $this->createStone();
        $this->assertSame($this->owner, $stone->getOwner());
        $this->assertInstanceOf(\DateTime::class, $stone->getCreatedOn());
        $this->assertSame('Hidden', $stone->getStatus());
    }

    public function testSetOwner()
    {
        $owner = $this->createPlayer();
        $stone = $this->createStone();
        $stone->setOwner($owner);
        $this->assertEquals($stone->getOwner(), $owner);
    }

    public function testSetCreatedOn()
    {
        $stone = $this->createStone();
        $stone->setCreatedOn(new \DateTime());
        $this->assertInstanceOf(\DateTime::class, $stone->getCreatedOn());
    }

    public function testSetStatus()
    {
        $string = 'status';
        $stone = $this->createStone();
        $stone->setStatus($string);
        $this->assertEquals($stone->getStatus(), $string);
    }
}