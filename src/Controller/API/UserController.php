<?php

namespace App\Controller\API;

use App\Entity\User;
use App\Traits\JsonResponseTrait;
use App\Transformer\UserDetailTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/profile', name: 'profile_')]
class UserController extends AbstractController
{
    use JsonResponseTrait;

    #[Route('', name: 'detail', methods: ['GET'])]
    public function detail(
        Security $security,
        UserDetailTransformer $userDetailTransformer,
    ): JsonResponse {
        /**
         * @var User $user
         */
        $user = $security->getUser();
        $result = $userDetailTransformer->toArray($user);

        return $this->success($result);
    }
}
