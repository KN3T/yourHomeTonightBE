<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Hotel;
use App\Entity\HotelImage;
use App\Entity\Room;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RoomFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getRoomData() as [$id, $number, $type, $price, $guest, $children, $asset, $description, $hotelId]) {
            $room = new Room();
            /**
             * @var Hotel $hotel
             */
            $hotel = $this->getReference('hotel_' . $hotelId);

            $room->setNumber($number)
                ->setType($type)
                ->setPrice($price)
                ->setGuest($guest)
                ->setChildren($children)
                ->setAsset($asset)
                ->setDescription($description)
                ->setHotel($hotel);
            $manager->persist($room);
            $this->addReference('room_' . $id, $room);
        }
        $manager->flush();
    }

    private function getRoomData(): array
    {
        return [
            [1, 45, 'Gold', 100, 2, 1, [], 'description', 1],
            [2, 30, 'Silver', 60, 2, 2, [], 'description', 1],
            [3, 75, 'Diamond', 200, 3, 2, [], 'description', 1],
            [4, 23, 'Normal', 20, 2, 1, [], 'description', 2],
            [5, 45, 'Diamond', 60, 2, 1, [], 'description', 2],
            [6, 34, 'Silver', 34, 5, 1, [], 'description', 2],
            [7, 40, 'Normal', 36, 3, 1, [], 'description', 3],
            [8, 10, 'Diamond', 76, 5, 1, [], 'description', 3],
            [9, 24, 'Silver', 54, 2, 1, [], 'description', 3],
        ];
    }

    public function getDependencies()
    {
        return [
            HotelFixtures::class,
        ];
    }
}
