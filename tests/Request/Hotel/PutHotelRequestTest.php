<?php

namespace App\Tests\Request\Hotel;

use App\Request\Hotel\PutHotelRequest;
use PHPUnit\Framework\TestCase;

class PutHotelRequestTest extends TestCase
{
    public function testGetName()
    {
        $putHotelRequest = new PutHotelRequest();
        $putHotelRequest->setName('Muong Thanh');
        $result = $putHotelRequest->getName();
        $this->assertEquals('Muong Thanh', $result);
    }

    public function testGetEmail()
    {
        $putHotelRequest = new PutHotelRequest();
        $putHotelRequest->setEmail('muongthanh@gg.com');
        $result = $putHotelRequest->getEmail();
        $this->assertEquals('muongthanh@gg.com', $result);
    }

    public function testGetPhone()
    {
        $putHotelRequest = new PutHotelRequest();
        $putHotelRequest->setPhone('0948474747');
        $result = $putHotelRequest->getPhone();
        $this->assertEquals('0948474747', $result);
    }

    public function testGetCity()
    {
        $putHotelRequest = new PutHotelRequest();
        $putHotelRequest->setCity('Can Tho');
        $result = $putHotelRequest->getCity();
        $this->assertEquals('Can Tho', $result);
    }

    public function testGetAddress()
    {
        $putHotelRequest = new PutHotelRequest();
        $putHotelRequest->setAddress('3/2');
        $result = $putHotelRequest->getAddress();
        $this->assertEquals('3/2', $result);
    }

    public function testGetProvince()
    {
        $putHotelRequest = new PutHotelRequest();
        $putHotelRequest->setProvince('Can Tho');
        $result = $putHotelRequest->getProvince();
        $this->assertEquals('Can Tho', $result);
    }

    public function testGetDescription()
    {
        $putHotelRequest = new PutHotelRequest();
        $putHotelRequest->setDescription('This is description');
        $result = $putHotelRequest->getDescription();
        $this->assertEquals('This is description', $result);
    }

    public function testGetImages()
    {
        $putHotelRequest = new PutHotelRequest();
        $putHotelRequest->setImages([1, 2, 3]);
        $result = $putHotelRequest->getImages();
        $this->assertEquals([1, 2, 3], $result);
    }

    public function testGetRules()
    {
        $putHotelRequest = new PutHotelRequest();
        $putHotelRequest->setRules(['TV', 'Coffee']);
        $result = $putHotelRequest->getRules();
        $this->assertEquals(['TV', 'Coffee'], $result);
    }
}
