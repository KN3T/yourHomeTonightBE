<?php

namespace App\Tests\Request\Hotel;

use App\Request\Hotel\CreateHotelRequest;
use PHPUnit\Framework\TestCase;

class CreateHotelRequestTest extends TestCase
{
    public function testGetName()
    {
        $createHotelRequest = new CreateHotelRequest();
        $createHotelRequest->setName('Muong Thanh');
        $result = $createHotelRequest->getName();
        $this->assertEquals('Muong Thanh', $result);
    }

    public function testGetEmail()
    {
        $createHotelRequest = new CreateHotelRequest();
        $createHotelRequest->setEmail('muongthanh@gg.com');
        $result = $createHotelRequest->getEmail();
        $this->assertEquals('muongthanh@gg.com', $result);
    }

    public function testGetPhone()
    {
        $createHotelRequest = new CreateHotelRequest();
        $createHotelRequest->setPhone('0948474747');
        $result = $createHotelRequest->getPhone();
        $this->assertEquals('0948474747', $result);
    }

    public function testGetCity()
    {
        $createHotelRequest = new CreateHotelRequest();
        $createHotelRequest->setCity('Can Tho');
        $result = $createHotelRequest->getCity();
        $this->assertEquals('Can Tho', $result);
    }

    public function testGetAddress()
    {
        $createHotelRequest = new CreateHotelRequest();
        $createHotelRequest->setAddress('3/2');
        $result = $createHotelRequest->getAddress();
        $this->assertEquals('3/2', $result);
    }

    public function testGetProvince()
    {
        $createHotelRequest = new CreateHotelRequest();
        $createHotelRequest->setProvince('Can Tho');
        $result = $createHotelRequest->getProvince();
        $this->assertEquals('Can Tho', $result);
    }

    public function testGetDescription()
    {
        $createHotelRequest = new CreateHotelRequest();
        $createHotelRequest->setDescription('This is description');
        $result = $createHotelRequest->getDescription();
        $this->assertEquals('This is description', $result);
    }

    public function testGetImages()
    {
        $createHotelRequest = new CreateHotelRequest();
        $createHotelRequest->setImages([1, 2, 3]);
        $result = $createHotelRequest->getImages();
        $this->assertEquals([1, 2, 3], $result);
    }

    public function testGetRules()
    {
        $createHotelRequest = new CreateHotelRequest();
        $createHotelRequest->setRules(['TV', 'Coffee']);
        $result = $createHotelRequest->getRules();
        $this->assertEquals(['TV', 'Coffee'], $result);
    }
}
