<?php

namespace App\EventListener;

use App\Entity\User;
use App\Traits\JsonResponseTrait;
use App\Transformer\UserTransformer;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener
{
    use JsonResponseTrait;

    private UserTransformer $userTransformer;

    public function __construct(UserTransformer $userTransformer)
    {
        $this->userTransformer = $userTransformer;
    }

    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        /**
         * @var User $user
         */
        $user = $event->getUser();
        $token = $event->getData()['token'];

        $userJson = $this->userTransformer->toArray($user);
        $userJson['token'] = $token;
        if ($user->isHotel() && null !== !$user->getHotel()) {
            $userJson['hotelId'] = $user->getHotel()->getId();
        }
        $data = [
            'status' => 'success',
            'data' => $userJson,
        ];

        $event->setData($data);
    }
}
