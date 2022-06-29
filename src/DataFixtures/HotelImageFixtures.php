<?php

namespace App\DataFixtures;

use App\Entity\Hotel;
use App\Entity\HotelImage;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class HotelImageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getHotelData() as [$id, $hotelId, $imageId]) {
            $hotelImage = new HotelImage();
            /**
             * @var Hotel $hotel
             */
            $hotel = $this->getReference('hotel_'.$hotelId);

            /**
             * @var Image $image
             */
            $image = $this->getReference('image_'.$imageId);

            $hotelImage->setHotel($hotel)->setImage($image);
            $manager->persist($hotelImage);
            $this->addReference('hotel_image_'.$id, $hotelImage);
        }
        $manager->flush();
    }

    private function getHotelData(): array
    {
        return [
            [1, 1, 1],
            [2, 1, 2],
            [3, 2, 5],
            [4, 2, 6],
            [5, 3, 3],
            [6, 3, 4],
        ];
    }

    public function getDependencies(): array
    {
        return [
            HotelFixtures::class,
            ImageFixtures::class,
        ];
    }
}
