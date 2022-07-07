<?php

namespace App\Tests\Transformer;

use App\Entity\User;
use App\Transformer\UserTransformer;
use PHPUnit\Framework\TestCase;

class UserTransformerTest extends TestCase
{
    /**
     * @var UserTransformer
     */
    private $userTransformer;

    protected function setUp(): void
    {
        $this->userTransformer = new UserTransformer();
    }

    public function testTransform(): void
    {
        $user = new User();
        $user->setEmail('user@g.com');
        $user->setFullName('User G');
        $user->setPhone('123-456-7890');
        $user->setRoles(['ROLE_USER']);
        $userTransformer = new UserTransformer();
        $userTransformed = $userTransformer->toArray($user);
        $expectedArray = [
            "id" => null,
            "email" => "user@g.com",
            "fullName" => "User G",
            "phone" => "123-456-7890",
            "role" => "ROLE_USER",
        ];
        $this->assertEquals($expectedArray, $userTransformed);

    }
}
