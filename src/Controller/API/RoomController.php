<?php

namespace App\Controller\API;

use App\Traits\JsonResponseTrait;
use App\Transformer\ValidatorTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/rooms', name: 'room_')]
class RoomController extends AbstractController
{
    use JsonResponseTrait;

    private ValidatorTransformer $validatorTransformer;
    private ValidatorInterface $validator;

    public function __construct(ValidatorTransformer $validatorTransformer, ValidatorInterface $validator)
    {
        $this->validatorTransformer = $validatorTransformer;
        $this->validator = $validator;
    }
}
