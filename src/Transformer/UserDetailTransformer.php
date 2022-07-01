<?php

namespace App\Transformer;

use App\Entity\User;

class UserDetailTransformer extends BaseTransformer
{
    public const ALLOW = ['id', 'email', 'fullName', 'phone'];

    public function toArray(User $user): array
    {
        return $this->transform($user, static::ALLOW);
    }
}
