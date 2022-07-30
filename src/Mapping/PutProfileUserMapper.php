<?php

namespace App\Mapping;

use App\Entity\User;
use App\Request\Profile\PutProfileRequest;

class PutProfileUserMapper
{
    public function mapping(PutProfileRequest $profileRequest, User $user): User
    {
        $user->setFullName($profileRequest->getFullName());
        $user->setPhone($profileRequest->getPhone());

        return $user;
    }
}
