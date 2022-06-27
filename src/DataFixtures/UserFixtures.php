<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
    }

    private function loadUsers(ObjectManager $manager): void
    {
        foreach ($this->getUserData() as [$id, $email, $fullName, $password, $roles]) {
            $user = new User();
            $user->setEmail($email);
            $user->setFullName($fullName);
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
            $user->setRoles($roles);

            $manager->persist($user);
            $this->addReference('user_'.$id, $user);
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            [1, 'admin@gg.com', 'Admin', '123456', ['ROLE_ADMIN']],
            [2, 'hotel@gg.com', 'Hotel', '123456', ['ROLE_HOTEL']],
            [3, 'user@gg.com', 'User', '123456', ['ROLE_USER']],
        ];
    }
}
