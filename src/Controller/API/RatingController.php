<?php

namespace App\Controller\API;

use App\Entity\Booking;
use App\Entity\User;
use App\Repository\RatingRepository;
use App\Request\Rating\CreateRatingRequest;
use App\Request\Rating\PutRatingRequest;
use App\Service\RatingService;
use App\Traits\JsonResponseTrait;
use App\Transformer\RatingTransformer;
use App\Transformer\ValidatorTransformer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RatingController extends AbstractController
{
    use JsonResponseTrait;

    private ValidatorTransformer $validatorTransformer;
    private ValidatorInterface $validator;

    public function __construct(ValidatorTransformer $validatorTransformer, ValidatorInterface $validator)
    {
        $this->validatorTransformer = $validatorTransformer;
        $this->validator = $validator;
    }

    #[Route('/bookings/{id}/rating', name: 'create_rating', methods: ['POST'])]
    public function createRating(
        Request $request,
        Security $security,
        Booking $booking,
        CreateRatingRequest $createRatingRequest,
        RatingService $ratingService,
        RatingTransformer $ratingTransformer,
    ): JsonResponse {
        /**
         * @var User $currentUser
         */
        $currentUser = $security->getUser();
        if ($this->checkUserCreateBooking($booking, $currentUser)) {
            return $this->error('Cannot create review, invalid booking or review already created');
        }
        $request = json_decode($request->getContent(), true);
        $createRatingRequest->fromArray($request);
        $errors = $this->validator->validate($createRatingRequest);
        if (count($errors) > 0) {
            return $this->error($this->validatorTransformer->toArray($errors));
        }
        $rating = $ratingService->create($createRatingRequest, $booking);

        return $this->success($ratingTransformer->toArray($rating));
    }

    #[Route('/bookings/{id}/rating', name: 'put_rating', methods: ['PUT'])]
    public function putRating(
        Request $request,
        Security $security,
        Booking $booking,
        PutRatingRequest $putRatingRequest,
        RatingService $ratingService,
        RatingTransformer $ratingTransformer,
    ): JsonResponse {
        /**
         * @var User $currentUser
         */
        $currentUser = $security->getUser();
        if ($this->checkUserPutBooking($booking, $currentUser)) {
            return $this->error('Cannot update review, invalid booking or review has not been created yet');
        }
        $request = json_decode($request->getContent(), true);
        $putRatingRequest->fromArray($request);
        $errors = $this->validator->validate($putRatingRequest);
        if (count($errors) > 0) {
            return $this->error($this->validatorTransformer->toArray($errors));
        }
        $rating = $booking->getRating();
        $ratingService->put($putRatingRequest, $rating);

        return $this->success($ratingTransformer->toArray($rating));
    }

    #[Route('/bookings/{id}/rating', name: 'delete_rating', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteRating(Booking $booking, RatingRepository $ratingRepository): JsonResponse
    {
        $rating = $booking->getRating();
        $ratingRepository->remove($rating);

        return $this->success([], Response::HTTP_NO_CONTENT);
    }

    #[Route('/bookings/{id}/rating', name: 'detail_rating', methods: ['GET'])]
    public function detailRating(Booking $booking, RatingTransformer $ratingTransformer): JsonResponse
    {
        $rating = $booking->getRating();
        $result = $ratingTransformer->toArray($rating);

        return $this->success($result);
    }

    private function checkUserCreateBooking(Booking $booking, User $user): bool
    {
        return $booking->getUser() !== $user || null !== $booking->getRating();
    }

    private function checkUserPutBooking(Booking $booking, User $user): bool
    {
        return $booking->getUser() !== $user || null === $booking->getRating();
    }
}
