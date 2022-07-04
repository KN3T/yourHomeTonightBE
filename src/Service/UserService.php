<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Request\Profile\ChangePasswordRequest;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function changePassword(
        User $user,
        ChangePasswordRequest $changePasswordRequest,
    ): User {
        $hashedPassword = $this->passwordHasher->hashPassword($user, $changePasswordRequest->getNewPassword());
        $user->setPassword($hashedPassword);
        $this->userRepository->save($user);

        return $user;
    }
}
