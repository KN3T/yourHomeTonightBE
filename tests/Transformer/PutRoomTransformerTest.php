<?php

namespace App\Tests\Transformer;

use App\Entity\Image;
use App\Entity\Room;
use App\Entity\RoomImage;
use App\Transformer\PutRoomTransformer;
use App\Transformer\RoomImageTransformer;
use PHPUnit\Framework\TestCase;

class PutRoomTransformerTest extends TestCase
{
    public function testToArray()
    {
        $roomImageTransformer = new RoomImageTransformer();
        $roomImage = new RoomImage();
        $image = new Image();
        $roomImage->setImage($image);
        $room = new Room();
        $room->addRoomImage($roomImage);
        $putRoomTransformer = new PutRoomTransformer($roomImageTransformer);
        $result = $putRoomTransformer->toArray($room);
        $expected = [
            "id" => null,
            "number" => null,
            "type" => null,
            "price" => null,
            "adults" => null,
            "children" => null,
            "asset" => [],
            "beds" => null,
            "description" => null,
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
        $putRoomTransformer = new PutRoomTransformer($roomImageTransformer);
        $result = $putRoomTransformer->listToArray([$room]);
        $expected = [
            0 => [
                "id" => null,
                "number" => null,
                "type" => null,
                "price" => null,
                "adults" => null,
                "children" => null,
                "asset" => [],
                "beds" => null,
                "description" => null,
                "images" => [
                    0 => [
                        "imageId" => null,
                        "src" => null,
                    ],
                ],
            ],
        ];

        $this->assertEquals($expected, $result);
    }
}
