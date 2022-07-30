<?php

namespace App\Tests\Request\Profile;

use App\Request\Profile\PutProfileRequest;
use PHPUnit\Framework\TestCase;

class PutProfileRequestTest extends TestCase
{
    public function testGetFullName()
    {
        $listHotelRequest = new PutProfileRequest();
        $listHotelRequest->setFullName('Khoa Tran');
        $result = $listHotelRequest->getFullName();
        $this->assertEquals('Khoa Tran', $result);
    }

    public function testGetPhone()
    {
        $listHotelRequest = new PutProfileRequest();
        $listHotelRequest->setPhone('0947848474');
        $result = $listHotelRequest->getPhone();
        $this->assertEquals('0947848474', $result);
    }
}
