<?php

namespace App\DataFixtures;

use App\Entity\Hotel;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class HotelFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getHotelData() as [$id, $name, $description, $phoneNumber, $email, $rule]) {
            $hotel = new Hotel();
            /**
             * @var User $user
             */
            $user = $this->getReference('user_'.$id);

            $hotel->setName($name)
                ->setDescription($description)
                ->setPhone($phoneNumber)
                ->setEmail($email)
                ->setRules($rule)
                ->setUser($user);
            $manager->persist($hotel);
            $this->addReference('hotel_'.$id, $hotel);
        }
        $manager->flush();
    }

    private function getHotelData(): array
    {
        return [
            [1, 'Ninh Kieu', 'NK description', '097484748', 'ninhkieu@gg.com', ['No dog']],
            [2, 'Muong Thanh', 'MT description', '097484748', 'muongthanh@gg.com', ['No cat']],
            [3, 'Fortuneland', 'Fortuneland description', '097484748', 'fortuneland@gg.com', ['No human']],
        ];
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
