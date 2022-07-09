<?php

namespace App\Tests\Transformer;

use App\Entity\Room;
use App\Transformer\CreateRoomTransformer;
use App\Transformer\RoomImageTransformer;
use PHPUnit\Framework\TestCase;

class CreateRoomTransformerTest extends TestCase
{
    public function testToArray()
    {
        $roomImageTransformerMock = $this->getMockBuilder(RoomImageTransformer::class)->disableOriginalConstructor()->getMock();
        $createRoomTransformer = new CreateRoomTransformer($roomImageTransformerMock);
        $room = new Room();
        $room->setNumber(3);
        $room->setType(4);
        $room->setPrice(5);
        $room->setAdults(6);
        $room->setChildren(7);
        $room->setAsset([]);
        $room->setBeds(9);
        $room->setDescription("description");
        $roomImageTransformerMock->expects($this->once())->method('listToArray')->with($room->getRoomImages())->willReturn(['images' => 'John Doe']);
        $result = $createRoomTransformer->toArray($room);
        $this->assertEquals(
            [
                'id' => null,
                'number' => 3,
                'type' => 4,
                'price' => 5,
                'adults' => 6,
                'children' => 7,
                'asset' => [],
                'beds' => 9,
                'description' => "description",
                'images' => ['images' => 'John Doe'],
            ],
            $result
        );
    }

    public function testListToArray()
    {
        $roomImageTransformerMock = $this->getMockBuilder(RoomImageTransformer::class)->disableOriginalConstructor()->getMock();
        $createRoomTransformer = new CreateRoomTransformer($roomImageTransformerMock);
        $room = new Room();
        $room->setNumber(3);
        $room->setType(4);
        $room->setPrice(5);
        $room->setAdults(6);
        $room->setChildren(7);
        $room->setAsset([]);
        $room->setBeds(9);
        $room->setDescription("description");
        $roomImageTransformerMock->expects($this->once())->method('listToArray')->with($room->getRoomImages())->willReturn(['images' => 'John Doe']);
        $result = $createRoomTransformer->listToArray([$room]);
        $this->assertEquals(
            [
                [
                    'id' => null,
                    'number' => 3,
                    'type' => 4,
                    'price' => 5,
                    'adults' => 6,
                    'children' => 7,
                    'asset' => [],
                    'beds' => 9,
                    'description' => "description",
                    'images' => ['images' => 'John Doe'],
                ],
            ],
            $result
        );
    }
}
