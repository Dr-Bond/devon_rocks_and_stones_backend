<?php

namespace App\Tests;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private $email;

    public function setUp()
    {
        $this->email = 'test@email.com';
    }

    private function createUser()
    {
        return new User($this->email);
    }

    public function testConstructor()
    {
        $user = $this->createUser();
        $this->assertSame($this->email, $user->getEmail());
        $this->assertSame($this->email, $user->getUsername());
        $this->assertInstanceOf(\DateTime::class,$user->getCreatedOn());
    }

    public function testSetPassword()
    {
        $string = 'password';
        $user = $this->createUser();
        $user->setPassword($string);
        $this->assertEquals($user->getPassword(), $string);
    }

    public function testSetEmail()
    {
        $string = 'emailaddress@test.com';
        $user = $this->createUser();
        $user->setEmail($string);
        $this->assertEquals($user->getEmail(), $string);
    }

    public function testSetLastLogin()
    {
        $user = $this->createUser();
        $user->setLastLogin(new \DateTime());
        $this->assertInstanceOf(\DateTime::class,$user->getLastLogin());
    }

    public function testSetApiToken()
    {
        $string = 'apitoken';
        $user = $this->createUser();
        $user->setApiToken($string);
        $this->assertEquals($user->getApiToken(), $string);
    }

    public function testSetDeletedOn()
    {
        $user = $this->createUser();
        $user->setDeletedOn(new \DateTime());
        $this->assertInstanceOf(\DateTime::class,$user->getDeletedOn());
    }

    public function testSetInactiveEmailSentOn()
    {
        $user = $this->createUser();
        $user->setInactiveEmailSentOn(new \DateTime());
        $this->assertInstanceOf(\DateTime::class,$user->getInactiveEmailSentOn());
    }
}