<?php

namespace App\Tests\Entity;

use App\Entity\Address;
use App\Entity\Hotel;
use App\Entity\HotelImage;
use App\Entity\Room;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class HotelTest extends TestCase
{
    public function testHotel()
    {
        $hotel = new Hotel();
        $date = new \DateTimeImmutable('now');
        $address = new Address();
        $user = new User();
        $room = new Room();
        $image = new HotelImage();
        $rules = [
            'rules' => [
                'rule1',
                'rule2',
                'rule3',
            ],
        ];
        $hotel->setName('Hotel');
        $hotel->setEmail('hotel@gg.com');
        $hotel->setPhone('123456789');
        $hotel->setDescription('Hotel description');
        $hotel->setCreatedAt($date);
        $hotel->setUpdatedAt($date);
        $hotel->setDeletedAt($date);
        $hotel->setAddress($address);
        $hotel->setUser($user);
        $hotel->addRoom($room);
        $hotel->removeRoom($room);
        $hotel->addHotelImage($image);
        $hotel->removeHotelImage($image);
        $hotel->setRules($rules);
        $this->assertEquals($hotel->getName(), 'Hotel');
        $this->assertEquals($hotel->getEmail(), 'hotel@gg.com');
        $this->assertEquals($hotel->getPhone(), '123456789');
        $this->assertEquals($hotel->getDescription(), 'Hotel description');
        $this->assertEquals($hotel->getCreatedAt(), $date);
        $this->assertEquals($hotel->getUpdatedAt(), $date);
        $this->assertEquals($hotel->getId(), null);
        $this->assertEquals($hotel->getDeletedAt(), $date);
        $this->assertEquals($hotel->getAddress(), $address);
        $this->assertEquals($hotel->getUser(), $user);
        $this->assertEquals($hotel->getRooms()->toArray(), []);
        $this->assertEquals($hotel->getHotelImages()->toArray(), []);
        $this->assertEquals($hotel->getRules(), $rules);

   }
}
