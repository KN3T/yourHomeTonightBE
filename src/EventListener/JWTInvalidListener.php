<?php

namespace App\EventListener;

use App\Traits\JsonResponseTrait;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Symfony\Component\HttpFoundation\Response;

class JWTInvalidListener
{
    use JsonResponseTrait;

    public function onJWTInvalid(JWTInvalidEvent $event): void
    {
        $response = $this->error('Unauthorized', Response::HTTP_UNAUTHORIZED);
        $event->setResponse($response);
    }

    public function onJWTNotFound(JWTNotFoundEvent $event): void
    {
        $response = $this->error('Unauthorized', Response::HTTP_UNAUTHORIZED);
        $event->setResponse($response);
    }
}
