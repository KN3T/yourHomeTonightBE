<?php

namespace App\Tests\Transformer;

use App\Entity\Address;
use App\Entity\Hotel;
use App\Entity\HotelImage;
use App\Transformer\AddressTransformer;
use App\Transformer\HotelImageTransformer;
use App\Transformer\HotelTransformer;
use PHPUnit\Framework\TestCase;

class HotelTransformerTest extends TestCase
{
    public function testToArray()
    {
        $addressTransformerMock = $this->getMockBuilder(AddressTransformer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $hotelImageTransformerMock = $this->getMockBuilder(HotelImageTransformer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $addressTransformerMock->method('toArray')->willReturn(['address']);
        $hotelImageTransformerMock->method('listToArray')->willReturn(['images']);
        $hotelTransformer = new HotelTransformer($addressTransformerMock, $hotelImageTransformerMock);
        $hotel = new Hotel();
        $hotel->setName('name');
        $hotel->setDescription('description');
        $hotel->setPhone('0948474747');
        $hotel->setEmail('email@gg.com');
        $hotel->setRules([]);
        $address = new Address();
        $hotel->setAddress($address);
        $imageHotel = new HotelImage();
        $hotel->addHotelImage($imageHotel);
        $result = $hotelTransformer->toArray($hotel);
        $this->assertEquals([
            'id' => null,
            'name' => 'name',
            'description' => 'description',
            'phone' => '0948474747',
            'email' => 'email@gg.com',
            'rules' => [],
            'address' => ['address'],
            'images' => ['images']
        ], $result);
    }

    public function testListToArray()
    {
        $hotel = $this->getMockBuilder(Hotel::class)
            ->disableOriginalConstructor()
            ->getMock();
        $hotel->method('getAddress')->willReturn(new Address());
        $hotels = [
            $hotel,
            $hotel,
            $hotel,
        ];
        $addressTransformerMock = $this->getMockBuilder(AddressTransformer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $hotelImageTransformerMock = $this->getMockBuilder(HotelImageTransformer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $hotelImageTransformerMock->method('listToArray')->willReturn(['images']);
        $hotelTransformer = new HotelTransformer($addressTransformerMock, $hotelImageTransformerMock);
        $result = $hotelTransformer->listToArray($hotels);
        $this->assertCount(3, $result);
    }
}
