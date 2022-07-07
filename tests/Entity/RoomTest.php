<?php

namespace App\Tests\Entity;

use App\Entity\Booking;
use App\Entity\Hotel;
use App\Entity\Room;
use App\Entity\RoomImage;
use PHPUnit\Framework\TestCase;

class RoomTest extends TestCase
{
    public function testRoom()
    {
        $room = new Room();
        $date = new \DateTimeImmutable('now');
        $hotel = new Hotel();
        $roomImage = new RoomImage();
        $booking = new Booking();
        $room->setNumber(1);
        $room->setType('Standard');
        $room->setPrice(100);
        $room->setBeds(2);
        $room->setDescription('This is a room');
        $room->setAdults(2);
        $room->setChildren(0);
        $room->setAsset([]);
        $room->setCreatedAt($date);
        $room->setUpdatedAt($date);
        $room->setDeletedAt($date);
        $room->setHotel($hotel);
        $room->addRoomImage($roomImage);
        $room->removeRoomImage($roomImage);
        $room->addBooking($booking);
        $room->removeBooking($booking);
        $this->assertEquals($room->getNumber(), 1);
        $this->assertEquals($room->getType(), 'Standard');
        $this->assertEquals($room->getPrice(), 100);
        $this->assertEquals($room->getBeds(), 2);
        $this->assertEquals($room->getDescription(), 'This is a room');
        $this->assertEquals($room->getAdults(), 2);
        $this->assertEquals($room->getChildren(), 0);
        $this->assertEquals($room->getAsset(), []);
        $this->assertEquals($room->getCreatedAt(), $date);
        $this->assertEquals($room->getUpdatedAt(), $date);
        $this->assertEquals($room->getDeletedAt(), $date);
        $this->assertEquals($room->getHotel(), $hotel);
        $this->assertEquals($room->getRoomImages()->toArray(), []);
        $this->assertEquals($room->getBookings()->toArray(), []);
        $this->assertEquals($room->getId(), null);

    }
}
