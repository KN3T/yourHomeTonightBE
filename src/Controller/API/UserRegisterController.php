<?php

namespace App\Controller\API;

use App\Mapping\UserRegisterRequestUserMapper;
use App\Repository\UserRepository;
use App\Request\User\UserRegisterRequest;
use App\Traits\JsonResponseTrait;
use App\Transformer\UserTransformer;
use App\Transformer\ValidatorTransformer;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        UserRepository $userRepository,
        UserTransformer $userTransformer,
        UserRegisterRequestUserMapper $mapper,
        Request $request,
        JWTTokenManagerInterface $JWTTokenManager,
    ): JsonResponse {
        $jsonRequest = json_decode($request->getContent(), true);
        $userRegisterRequest->fromArray($jsonRequest);
        if ($userRepository->findOneBy(['email' => $userRegisterRequest->getEmail()])) {
            return $this->error('Email already exists', Response::HTTP_CONFLICT);
        }
        $errors = $this->validator->validate($userRegisterRequest);
        if (count($errors) > 0) {
            return $this->error($this->validatorTransformer->toArray($errors));
        }
        $user = $mapper->mapping($userRegisterRequest);
        $userRepository->save($user);
        $token = $JWTTokenManager->create($user);
        $userResult = $userTransformer->toArray($user);
        $userResult['token'] = $token;

        return $this->success($userResult);
    }
}
