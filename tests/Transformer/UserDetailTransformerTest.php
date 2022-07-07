<?php

namespace App\Tests\Transformer;

use App\Entity\User;
use App\Transformer\UserDetailTransformer;
use PHPUnit\Framework\TestCase;

class UserDetailTransformerTest extends TestCase
{
    public function testTransform(): void
    {
        $user = new User();
        $user->setEmail('user@g.com');
        $user->setFullName('User G');
        $user->setPhone('123-456-7890');
        $userTransformer = new UserDetailTransformer();
        $userTransformed = $userTransformer->toArray($user);
        $expectedArray = [
            "id" => null,
            "email" => "user@g.com",
            "fullName" => "User G",
            "phone" => "123-456-7890",
        ];
        $this->assertEquals($expectedArray, $userTransformed);

    }
}
