<?php

namespace App\Tests\Entity;

use App\Entity\Image;
use App\Entity\Room;
use App\Entity\RoomImage;
use PHPUnit\Framework\TestCase;

class RoomImageTest extends TestCase
{
    public function testRoomImage()
    {
        $roomImage = new RoomImage();
        $image = new Image();
        $room = new Room();
        $roomImage->setImage($image);
        $roomImage->setRoom($room);
        $this->assertEquals($roomImage->getImage(), $image);
        $this->assertEquals($roomImage->getRoom(), $room);
        $this->assertEquals($roomImage->getId(), null);
    }
}
