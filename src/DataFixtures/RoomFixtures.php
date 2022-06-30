<?php

namespace App\DataFixtures;

use App\Entity\Hotel;
use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RoomFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getRoomData() as [$id, $beds, $number, $type, $price, $guest, $children, $asset, $description, $hotelId]) {
            $room = new Room();
            /**
             * @var Hotel $hotel
             */
            $hotel = $this->getReference('hotel_'.$hotelId);

            $room->setNumber($number)
                ->setType($type)
                ->setPrice($price)
                ->setAdults($guest)
                ->setBeds($beds)
                ->setChildren($children)
                ->setAsset($asset)
                ->setDescription($description)
                ->setHotel($hotel);
            $manager->persist($room);
            $this->addReference('room_'.$id, $room);
        }
        $manager->flush();
    }

    private function getRoomData(): array
    {
        return [
            [1, 2, 45, 'Gold', 100, 2, 1, [], 'description', 1],
            [2, 2, 30, 'Silver', 60, 2, 2, [], 'description', 1],
            [3, 2, 75, 'Diamond', 200, 3, 2, [], 'description', 1],
            [4, 2, 23, 'Normal', 20, 2, 1, [], 'description', 2],
            [5, 2, 45, 'Diamond', 60, 2, 1, [], 'description', 2],
            [6, 2, 34, 'Silver', 34, 5, 1, [], 'description', 2],
            [7, 2, 40, 'Normal', 36, 3, 1, [], 'description', 3],
            [8, 2, 10, 'Diamond', 76, 5, 1, [], 'description', 3],
            [9, 2, 24, 'Silver', 54, 2, 1, [], 'description', 3],
        ];
    }

    public function getDependencies()
    {
        return [
            HotelFixtures::class,
        ];
    }
}
