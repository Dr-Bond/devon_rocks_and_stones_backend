<?php

namespace App\Tests;

use App\Entity\Location;
use App\Entity\Player;
use App\Entity\Stone;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class LocationTest extends TestCase
{
    private $hiddenBy;
    private $stone;
    private $area;

    public function setUp()
    {
        $this->hiddenBy = $this->createPlayer();
        $this->stone = $this->createStone();
        $this->area = 'area';
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
        return new Stone($this->createPlayer());
    }

    public function createLocation()
    {
        return new Location($this->hiddenBy, $this->stone, $this->area);
    }

    public function testConstructor()
    {
        $location = $this->createLocation();
        $this->assertSame($this->hiddenBy, $location->getHiddenBy());
        $this->assertInstanceOf(\DateTime::class, $location->getHiddenOn());
        $this->assertSame('Hidden', $location->getStone()->getStatus());
        $this->assertSame($this->area, $location->getArea());
    }

    public function testSetHiddenBy()
    {
        $hiddenBy = $this->createPlayer();
        $location = $this->createLocation();
        $location->setHiddenBy($hiddenBy);
        $this->assertEquals($location->getHiddenBy(), $hiddenBy);
    }

    public function testSetHiddenOn()
    {
        $location = $this->createLocation();
        $location->setHiddenOn(new \DateTime());
        $this->assertInstanceOf(\DateTime::class,$location->getHiddenOn());
    }

    public function testSetStone()
    {
        $stone = $this->createStone();
        $location = $this->createLocation();
        $location->setStone($stone);
        $this->assertEquals($location->getStone(), $stone);
    }

    public function testSetArea()
    {
        $string = 'test area';
        $location = $this->createLocation();
        $location->setArea($string);
        $this->assertEquals($location->getArea(), $string);
    }

    public function testSetFoundBy()
    {
        $foundBy = $this->createPlayer();
        $location = $this->createLocation();
        $location->setFoundBy($foundBy);
        $this->assertEquals($location->getFoundBy(), $foundBy);
    }

    public function testSetFoundOn()
    {
        $location = $this->createLocation();
        $location->setFoundOn(new \DateTime());
        $this->assertInstanceOf(\DateTime::class,$location->getFoundOn());
    }

    public function testSetKept()
    {
        $location = $this->createLocation();
        $location->setKept(true);
        $this->assertTrue($location->getKept());
    }

    public function testSetPreviousStone()
    {
        $previous = $this->createLocation();
        $location = $this->createLocation();
        $location->setPreviousLocation($previous);
        $this->assertEquals($location->getPreviousLocation(), $previous);
    }
}