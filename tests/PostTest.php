<?php

namespace App\Tests;

use App\Entity\Location;
use App\Entity\Player;
use App\Entity\Post;
use App\Entity\Stone;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    private $addedBy;
    private $content;

    public function setUp()
    {
        $this->addedBy = $this->createPlayer();
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

    public function createPost()
    {
        return new Post($this->addedBy, $this->content);
    }

    public function testConstructor()
    {
        $post = $this->createPost();
        $this->assertSame($this->addedBy, $post->getAddedBy());
        $this->assertInstanceOf(\DateTime::class, $post->getAddedOn());
        $this->assertSame($this->content, $post->getContent());
    }

    public function testSetAddedBy()
    {
        $addedBy = $this->createPlayer();
        $post = $this->createPost();
        $post->setAddedBy($addedBy);
        $this->assertEquals($post->getAddedBy(), $addedBy);
    }

    public function testSetAddedOn()
    {
        $post = $this->createPost();
        $post->setAddedOn(new \DateTime());
        $this->assertInstanceOf(\DateTime::class, $post->getAddedOn());
    }

    public function testSetContent()
    {
        $string = 'test content';
        $post = $this->createPost();
        $post->setContent($string);
        $this->assertEquals($post->getContent(), $string);
    }

    public function testSetImage()
    {
        $string = 'image';
        $post = $this->createPost();
        $post->setImage($string);
        $this->assertEquals($post->getImage(), $string);
    }
}