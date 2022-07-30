<?php

namespace App\Tests\Transformer;

use App\Entity\Image;
use App\Entity\Room;
use App\Entity\RoomImage;
use App\Transformer\ListRoomTransformer;
use App\Transformer\RoomImageTransformer;
use PHPUnit\Framework\TestCase;

class ListRoomTransformerTest extends TestCase
{
    public function testToArray()
    {
        $roomImageTransformer = new RoomImageTransformer();
        $roomImage = new RoomImage();
        $image = new Image();
        $roomImage->setImage($image);
        $room = new Room();
        $room->addRoomImage($roomImage);
        $listRoomTransformer = new ListRoomTransformer($roomImageTransformer);
        $roomArray = [
            0 => $room,
            "rating" => 5,
        ];
        $result = $listRoomTransformer->toArray($roomArray);
        $expected = [
            "id" => null,
            "number" => null,
            "type" => null,
            "beds" => null,
            "price" => null,
            "adults" => null,
            "children" => null,
            "asset" => [],
            "description" => null,
            "rating" => 5.0,
            "images" => [
                0 => [
                    "imageId" => null,
                    "src" => null,
                ],
            ],
        ];


        $this->assertEquals($expected, $result);
    }

    public function testListToArray()
    {
        $roomImageTransformer = new RoomImageTransformer();
        $roomImage = new RoomImage();
        $image = new Image();
        $roomImage->setImage($image);
        $room = new Room();
        $room->addRoomImage($roomImage);
        $listRoomTransformer = new ListRoomTransformer($roomImageTransformer);
        $roomArray = [
            "total" => 1,
            [
                0 => $room,
                "rating" => 5,
            ],
        ];
        $result = $listRoomTransformer->listToArray($roomArray);
        $expected = [
            "total" => 1,
            "rooms" => [
                0 => [
                    "id" => null,
                    "number" => null,
                    "type" => null,
                    "beds" => null,
                    "price" => null,
                    "adults" => null,
                    "children" => null,
                    "asset" => [],
                    "description" => null,
                    "rating" => 5.0,
                    "images" => [
                        0 => [
                            "imageId" => null,
                            "src" => null,
                        ],
                    ],
                ],
            ],
        ];
        $this->assertEquals($expected, $result);
    }
}
