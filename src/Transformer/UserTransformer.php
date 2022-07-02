<?php

namespace App\Transformer;

use App\Entity\User;

class UserTransformer extends BaseTransformer
{
    public const ALLOW = ['id', 'email', 'fullName', 'phone'];

    public function toArray(User $user): array
    {
        $userResult = $this->transform($user, static::ALLOW);
        $userResult['role'] = $user->getRoles()[0];

        return $userResult;
    }
}
