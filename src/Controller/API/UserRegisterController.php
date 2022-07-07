<?php

namespace App\Controller\API;

use App\Mapping\UserRegisterRequestUserMapper;
use App\Request\User\UserRegisterRequest;
use App\Service\UserService;
use App\Traits\JsonResponseTrait;
use App\Transformer\UserTransformer;
use App\Transformer\ValidatorTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserRegisterController extends AbstractController
{
    use JsonResponseTrait;

    private ValidatorTransformer $validatorTransformer;
    private ValidatorInterface $validator;

    public function __construct(ValidatorTransformer $validatorTransformer, ValidatorInterface $validator)
    {
        $this->validatorTransformer = $validatorTransformer;
        $this->validator = $validator;
    }

    #[Route('/register', name: 'register', methods: ['POST'])]
    public function register(
        UserRegisterRequest $userRegisterRequest,
        UserService $userService,
        UserTransformer $userTransformer,
        UserRegisterRequestUserMapper $mapper,
        Request $request,
    ): JsonResponse {
        $jsonRequest = json_decode($request->getContent(), true);
        $userRegisterRequest->fromArray($jsonRequest);
        $errors = $this->validator->validate($userRegisterRequest);
        if (count($errors) > 0) {
            return $this->error($this->validatorTransformer->toArray($errors));
        }
        $user = $mapper->mapping($userRegisterRequest);
        $userService->create($user);
        $userResult = $userTransformer->toArray($user);

        return $this->success($userResult);
    }
}
