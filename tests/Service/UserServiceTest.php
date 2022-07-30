<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Request\Profile\ChangePasswordRequest;
use App\Service\UserService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserServiceTest extends TestCase
{
    public function testChangePassword()
    {
        $user = new User();
        $changePasswordRequest = new ChangePasswordRequest();
        $changePasswordRequest->setNewPassword('newPassword');
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $passwordHasherMock = $this->getMockBuilder(UserPasswordHasherInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $userService = new UserService($userRepositoryMock, $passwordHasherMock);
        $userRepositoryMock->expects($this->once())
            ->method('save')
            ->willReturn($user);
        $passwordHasherMock->expects($this->once())
            ->method('hashPassword')
            ->willReturn('hashedPassword');
        $userService->changePassword($user, $changePasswordRequest);
        $this->assertEquals('hashedPassword', $user->getPassword());
    }
}
