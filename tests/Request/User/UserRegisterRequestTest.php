<?php

namespace App\Tests\Request\User;

use App\Request\User\UserRegisterRequest;
use PHPUnit\Framework\TestCase;

class UserRegisterRequestTest extends TestCase
{
    public function testUserRegisterRequest()
    {
        $request = new UserRegisterRequest();
        $request->setEmail('email');
        $request->setPassword('password');
        $request->setConfirmPassword('password');
        $request->setFullName('fullName');
        $request->setIsHotel(true);

        $this->assertEquals('email', $request->getEmail());
        $this->assertEquals('password', $request->getPassword());
        $this->assertEquals('password', $request->getConfirmPassword());
        $this->assertEquals('fullName', $request->getFullName());
        $this->assertEquals(true, $request->getIsHotel());
    }
}
