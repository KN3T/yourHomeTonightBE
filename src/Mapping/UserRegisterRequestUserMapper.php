<?php

namespace App\Mapping;

use App\Entity\User;
use App\Request\User\UserRegisterRequest;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegisterRequestUserMapper
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function mapping(UserRegisterRequest $userRegisterRequest): User
    {
        $user = new User();
        $user->setEmail($userRegisterRequest->getEmail());
        $password = $this->passwordHasher->hashPassword($user, $userRegisterRequest->getPassword());
        $user->setPassword($password);
        $user->setFullName($userRegisterRequest->getFullName());
        if ($userRegisterRequest->getIsHotel()) {
            $user->setRoles(['ROLE_HOTEL']);
        } else {
            $user->setRoles(['ROLE_USER']);
        }

        return $user;
    }
}
