<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Hotel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AddressFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getAddressData() as [$id, $address, $city, $province]) {
            $newAddress = new Address();
            /**
             * @var Hotel $hotel
             */
            $hotel = $this->getReference('hotel_'.$id);

            $newAddress->setAddress($address)
                ->setCity($city)
                ->setProvince($province)
                ->setHotel($hotel);
            $manager->persist($newAddress);
        }
        $manager->flush();
    }

    private function getAddressData(): array
    {
        return [
            [1, '3/2', 'Da Lat', 'Lam Dong'],
            [2, 'Mau Than', 'Can Tho', 'Can Tho'],
            [3, 'Vo Van Kiet', 'Da Nang', 'Da Nang'],
        ];
    }

    public function getDependencies()
    {
        return [
            HotelFixtures::class,
        ];
    }
}
