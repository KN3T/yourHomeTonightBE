<?php

namespace App\Controller\API;

use App\Entity\User;
use App\Mapping\PutProfileUserMapper;
use App\Repository\UserRepository;
use App\Request\Profile\ChangePasswordRequest;
use App\Request\Profile\PutProfileRequest;
use App\Service\UserService;
use App\Traits\JsonResponseTrait;
use App\Transformer\BookingTransformer;
use App\Transformer\UserDetailTransformer;
use App\Transformer\UserTransformer;
use App\Transformer\ValidatorTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/profile', name: 'profile_')]
class ProfileController extends AbstractController
{
    use JsonResponseTrait;

    private ValidatorTransformer $validatorTransformer;
    private ValidatorInterface $validator;

    public function __construct(ValidatorTransformer $validatorTransformer, ValidatorInterface $validator)
    {
        $this->validatorTransformer = $validatorTransformer;
        $this->validator = $validator;
    }

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

    #[Route('/bookings', name: 'bookings', methods: ['GET'])]
    public function listBooking(
        Security $security,
        BookingTransformer $bookingTransformer,
    ): JsonResponse {
        /**
         * @var User $user
         */
        $user = $security->getUser();
        $bookings = $user->getBookings()->toArray();
        $result = $bookingTransformer->listToArray($bookings);

        return $this->success($result);
    }

    #[Route('', name: 'put', methods: ['PUT'])]
    public function putProfile(
        Security $security,
        PutProfileRequest $putProfileRequest,
        PutProfileUserMapper $putProfileUserMapper,
        Request $request,
        UserTransformer $userTransformer,
        UserRepository $userRepository,
    ): JsonResponse {
        /**
         * @var User $user
         */
        $user = $security->getUser();
        $jsonRequest = json_decode($request->getContent(), true);
        $putProfileRequest->fromArray($jsonRequest);
        $errors = $this->validator->validate($putProfileRequest);
        if (count($errors) > 0) {
            return $this->error($this->validatorTransformer->toArray($errors));
        }
        $putProfileUserMapper->mapping($putProfileRequest, $user);
        $userRepository->save($user);
        $result = $userTransformer->toArray($user);

        return $this->success($result);
    }

    #[Route('/changePassword', name: 'change_password', methods: ['POST'])]
    public function changePassword(
        Security $security,
        Request $request,
        UserTransformer $userTransformer,
        UserService $userService,
        ChangePasswordRequest $changePasswordRequest,
    ): JsonResponse {
        /**
         * @var User $user
         */
        $user = $security->getUser();
        $jsonRequest = json_decode($request->getContent(), true);
        $changePasswordRequest->fromArray($jsonRequest);
        $errors = $this->validator->validate($changePasswordRequest);
        if (count($errors) > 0) {
            return $this->error($this->validatorTransformer->toArray($errors));
        }
        $userService->changePassword($user, $changePasswordRequest);
        $result = $userTransformer->toArray($user);

        return $this->success($result);
    }
}
