<?php

namespace App\Tests;

use App\Entity\Clue;
use App\Entity\Location;
use App\Entity\Player;
use App\Entity\Stone;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class ClueTest extends TestCase
{
    private $addedBy;
    private $location;
    private $content;

    public function setUp()
    {
        $this->addedBy = $this->createPlayer();
        $this->location = $this->createLocation();
        $this->content = 'test content';
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
        return new Location($this->createPlayer(), $this->createStone(), 'test');
    }

    public function createClue()
    {
        return new Clue($this->addedBy, $this->location, $this->content);
    }

    public function testConstructor()
    {
        $clue = $this->createClue();
        $this->assertSame($this->addedBy, $clue->getAddedBy());
        $this->assertInstanceOf(\DateTime::class, $clue->getAddedOn());
        $this->assertSame($this->location, $clue->getLocation());
        $this->assertSame($this->content, $clue->getContent());
    }

    public function testSetAddedBy()
    {
        $addedBy = $this->createPlayer();
        $clue = $this->createClue();
        $clue->setAddedBy($addedBy);
        $this->assertEquals($clue->getAddedBy(), $addedBy);
    }

    public function testSetAddedOn()
    {
        $clue = $this->createClue();
        $clue->setAddedOn(new \DateTime());
        $this->assertInstanceOf(\DateTime::class,$clue->getAddedOn());
    }

    public function testSetLocation()
    {
        $location = $this->createLocation();
        $clue = $this->createClue();
        $clue->setLocation($location);
        $this->assertEquals($clue->getLocation(), $location);
    }

    public function testSetContent()
    {
        $string = 'test content';
        $clue = $this->createClue();
        $clue->setContent($string);
        $this->assertEquals($clue->getContent(), $string);
    }

    public function testSetImage()
    {
        $string = 'image';
        $clue = $this->createClue();
        $clue->setImage($string);
        $this->assertEquals($clue->getImage(), $string);
    }
}