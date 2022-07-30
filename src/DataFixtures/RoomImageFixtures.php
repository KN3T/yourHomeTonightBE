<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Room;
use App\Entity\RoomImage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RoomImageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getRoomData() as [$id, $roomId, $imageId]) {
            $roomImage = new RoomImage();
            /**
             * @var Room $room
             */
            $room = $this->getReference('room_'.$roomId);

            /**
             * @var Image $image
             */
            $image = $this->getReference('image_'.$imageId);

            $roomImage->setRoom($room)->setImage($image);
            $manager->persist($roomImage);
            $this->addReference('room_image_'.$id, $roomImage);
        }
        $manager->flush();
    }

    private function getRoomData(): array
    {
        return [
            [1, 1, 7],
            [2, 2, 8],
            [3, 3, 9],
            [4, 4, 10],
            [5, 5, 11],
            [6, 6, 12],
            [7, 7, 13],
            [8, 8, 14],
            [9, 9, 15],
        ];
    }

    public function getDependencies(): array
    {
        return [
            RoomFixtures::class,
            ImageFixtures::class,
        ];
    }
}
