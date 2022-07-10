<?php

namespace App\Tests\Transformer;

use App\Entity\Address;
use App\Entity\Hotel;
use App\Entity\HotelImage;
use App\Entity\Image;
use App\Transformer\AddressTransformer;
use App\Transformer\HotelImageTransformer;
use App\Transformer\ListHotelTransformer;
use PHPUnit\Framework\TestCase;

class ListHotelTransformerTest extends TestCase
{
    public function testToArray()
    {
        $address = new Address();
        $hotelImage = new HotelImage();
        $image = new Image();
        $hotelImage->setImage($image);
        $addressTransformer = new AddressTransformer();
        $hotelImageTransformer = new HotelImageTransformer();
        $listHotelTransformerTest = new ListHotelTransformer($addressTransformer, $hotelImageTransformer);
        $hotel = new Hotel();
        $hotel->setAddress($address);
        $hotel->addHotelImage($hotelImage);
        $hotelArray = [
            0 => $hotel,
            "price" => 100,
            "rating" => 5,
            "ratingCount" => 1,
        ];

        $expected = [
            "id" => null,
            "name" => null,
            "description" => null,
            "phone" => null,
            "email" => null,
            "rules" => [],
            "price" => 100,
            "rating" => 5.0,
            "ratingCount" => 1,
            "address" => [
                "id" => null,
                "city" => null,
                "province" => null,
                "address" => null,
            ],
            "images" => [
                0 => [
                    "imageId" => null,
                    "src" => null,
                ],
            ],
        ];

        $this->assertEquals($expected, $listHotelTransformerTest->toArray($hotelArray));
    }

    public function testListToArray()
    {
        $address = new Address();
        $hotelImage = new HotelImage();
        $image = new Image();
        $hotelImage->setImage($image);
        $addressTransformer = new AddressTransformer();
        $hotelImageTransformer = new HotelImageTransformer();
        $listHotelTransformerTest = new ListHotelTransformer($addressTransformer, $hotelImageTransformer);
        $hotel = new Hotel();
        $hotel->setAddress($address);
        $hotel->addHotelImage($hotelImage);
        $hotelArray = [
            "total" => 1,
            [
                0 => $hotel,
                "price" => 100,
                "rating" => 5,
                "ratingCount" => 1,
            ],
        ];

        $expected = [
            "total" => 1,
            "hotels" => [
                [
                    "id" => null,
                    "name" => null,
                    "description" => null,
                    "phone" => null,
                    "email" => null,
                    "rules" => [],
                    "price" => 100,
                    "rating" => 5.0,
                    "ratingCount" => 1,
                    "address" => [
                        "id" => null,
                        "city" => null,
                        "province" => null,
                        "address" => null,
                    ],
                    "images" => [
                        0 => [
                            "imageId" => null,
                            "src" => null,
                        ],
                    ],
                ],
            ],
        ];

        $this->assertEquals($expected, $listHotelTransformerTest->listToArray($hotelArray));
    }
}
