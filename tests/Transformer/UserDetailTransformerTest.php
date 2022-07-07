<?php

namespace App\Tests\Transformer;

use App\Entity\User;
use App\Transformer\UserDetailTransformer;
use PHPUnit\Framework\TestCase;

class UserDetailTransformerTest extends TestCase
{
    /**
     * @var UserDetailTransformer
     */
    private $userDetailTransformer;

    protected function setUp(): void
    {
        $this->userDetailTransformer = new UserDetailTransformer();
    }

    public function testTransform(): void
    {
        $user = new User();
        $user->setId(1);
        $user->setEmail();
    }
}
