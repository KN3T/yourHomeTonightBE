<?php

namespace App\Tests\Request\Profile;

use App\Request\Profile\ChangePasswordRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextFactory;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ChangePasswordRequestTest extends TestCase
{
    public function testGetCurrentPassword()
    {
        $listHotelRequest = new ChangePasswordRequest();
        $listHotelRequest->setCurrentPassword('password');
        $result = $listHotelRequest->getCurrentPassword();
        $this->assertEquals('password', $result);
    }

    public function testGetNewPassword()
    {
        $listHotelRequest = new ChangePasswordRequest();
        $listHotelRequest->setNewPassword('password');
        $result = $listHotelRequest->getNewPassword();
        $this->assertEquals('password', $result);
    }

    public function testGetConfirmPassword()
    {
        $listHotelRequest = new ChangePasswordRequest();
        $listHotelRequest->setConfirmPassword('password');
        $result = $listHotelRequest->getConfirmPassword();
        $this->assertEquals('password', $result);
    }
    
}
