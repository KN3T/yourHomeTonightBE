<?php

namespace App\Controller\API;

use App\Entity\Hotel;
use App\Entity\User;
use App\Repository\HotelRepository;
use App\Request\Hotel\CreateHotelRequest;
use App\Request\Hotel\ListHotelRequest;
use App\Request\Hotel\PutHotelRequest;
use App\Service\HotelService;
use App\Traits\JsonResponseTrait;
use App\Transformer\HotelTransformer;
use App\Transformer\ListHotelBookingsTransformer;
use App\Transformer\ListHotelRatingsTransformer;
use App\Transformer\ListHotelTransformer;
use App\Transformer\ValidatorTransformer;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/hotels', name: 'hotel_')]
class HotelController extends AbstractController
{
    use JsonResponseTrait;

    private ValidatorTransformer $validatorTransformer;
    private ValidatorInterface $validator;

    public function __construct(ValidatorTransformer $validatorTransformer, ValidatorInterface $validator)
    {
        $this->validatorTransformer = $validatorTransformer;
        $this->validator = $validator;
    }

    #[Route('', name: 'list', methods: ['GET'])]
    public function index(
        Request $request,
        ListHotelRequest $listHotelRequest,
        HotelService $hotelService,
        ListHotelTransformer $listHotelTransformer
    ): Response {
        $filters = $request->query->all();
        $hotelRequest = $listHotelRequest->fromArray($filters);
        $errors = $this->validator->validate($hotelRequest);
        if (count($errors) > 0) {
            $errorsTransformer = $this->validatorTransformer->toArray($errors);

            return $this->error($errorsTransformer);
        }
        $hotels = $hotelService->findAll($hotelRequest);
        $hotelsResult = $listHotelTransformer->listToArray($hotels);

        return $this->success($hotelsResult);
    }

    #[Route('/{id}', name: 'detail', methods: ['GET'])]
    public function detail(
        Hotel $hotel,
        ListHotelTransformer $hotelTransformer,
        HotelService $hotelService
    ): JsonResponse {
        $hotel = $hotelService->detail($hotel);

        return $this->success($hotelTransformer->toArray($hotel));
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Hotel $hotel, HotelRepository $hotelRepository): JsonResponse
    {
        $hotelRepository->remove($hotel);

        return $this->success([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @param CreateHotelRequest $createHotelRequest
     * @param Request            $request
     * @param HotelService       $hotelService
     * @param HotelTransformer   $hotelTransformer
     * @param Security           $security
     *
     * @return JsonResponse
     */
    #[Route('', name: 'create', methods: 'POST')]
    public function create(
        CreateHotelRequest $createHotelRequest,
        Request $request,
        HotelService $hotelService,
        HotelTransformer $hotelTransformer,
        Security $security,
    ): JsonResponse {
        /**
         * @var User $currentUser
         */
        $currentUser = $security->getUser();
        if ($hotelService->checkCreatedHotel($currentUser)) {
            return $this->error('Hotel already created', Response::HTTP_BAD_REQUEST);
        }
        $array = json_decode($request->getContent(), true);
        $createHotelRequest->fromArray($array);
        $errors = $this->validator->validate($createHotelRequest);
        if (count($errors) > 0) {
            return $this->error($this->validatorTransformer->toArray($errors), Response::HTTP_BAD_REQUEST);
        }
        $hotel = $hotelService->create($createHotelRequest);
        $result = $hotelTransformer->toArray($hotel);

        return $this->success($result, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'put', methods: 'PUT')]
    public function put(
        Hotel $hotel,
        Request $request,
        PutHotelRequest $putHotelRequest,
        HotelTransformer $hotelTransformer,
        Security $security,
        HotelService $hotelService,
    ): JsonResponse {
        /**
         * @var User $currentUser
         */
        $currentUser = $security->getUser();
        if (!$hotelService->checkHotelOwner($hotel, $currentUser)) {
            return $this->error('Access denied', Response::HTTP_FORBIDDEN);
        }
        $array = json_decode($request->getContent(), true);
        $putHotelRequest->fromArray($array);
        $errors = $this->validator->validate($putHotelRequest);
        if (count($errors) > 0) {
            return $this->error($this->validatorTransformer->toArray($errors), Response::HTTP_BAD_REQUEST);
        }
        $hotel = $hotelService->put($putHotelRequest, $hotel);
        $result = $hotelTransformer->toArray($hotel);

        return $this->success($result, Response::HTTP_OK);
    }

    #[Route('/{id}/ratings', name: 'list_ratings', methods: 'GET')]
    public function listRatings(
        Hotel $hotel,
        HotelRepository $hotelRepository,
        ListHotelRatingsTransformer $listHotelRatingsTransformer,
        Security $security,
        HotelService $hotelService,
    ): JsonResponse {
        $ratings = $hotelRepository->listRatings($hotel);
        $result = $listHotelRatingsTransformer->listToArray($ratings);

        return $this->success($result);
    }

    #[Route('/{id}/bookings', name: 'list_bookings', methods: 'GET')]
    #[IsGranted('ROLE_HOTEL')]
    public function listBookings(
        Hotel $hotel,
        HotelRepository $hotelRepository,
        ListHotelBookingsTransformer $bookingTransformer,
        HotelService $hotelService,
        Security $security,
    ): JsonResponse {
        /**
         * @var User $currentUser
         */
        $currentUser = $security->getUser();
        if (!$hotelService->checkHotelOwner($hotel, $currentUser)) {
            return $this->error('Access denied', Response::HTTP_FORBIDDEN);
        }
        $bookings = $hotelRepository->listBookings($hotel);
        $result = $bookingTransformer->listToArray($bookings);

        return $this->success($result);
    }

    /**
     * @throws Exception
     */
    #[Route('/{id}/revenue', name: 'get_revenue_yearly', methods: 'GET')]
    #[IsGranted('ROLE_HOTEL')]
    public function getYearlyRevenue(
        Hotel $hotel,
        HotelRepository $hotelRepository,
        HotelService $hotelService,
        Security $security,
    ): JsonResponse {
        /**
         * @var User $currentUser
         */
        $currentUser = $security->getUser();
        if (!$hotelService->checkHotelOwner($hotel, $currentUser)) {
            return $this->error('Access denied', Response::HTTP_FORBIDDEN);
        }
        $revenues = $hotelRepository->getYearlyRevenue($hotel);

        return $this->success($revenues);
    }

    /**
     * @throws Exception
     */
    #[Route('/{id}/revenue/last3Months', name: 'get_revenue_last3Months', methods: 'GET')]
    #[IsGranted('ROLE_HOTEL')]
    public function get3MonthsRevenue(
        Hotel $hotel,
        HotelRepository $hotelRepository,
        HotelService $hotelService,
        Security $security,
    ): JsonResponse {
        /**
         * @var User $currentUser
         */
        $currentUser = $security->getUser();
        if (!$hotelService->checkHotelOwner($hotel, $currentUser)) {
            return $this->error('Access denied', Response::HTTP_FORBIDDEN);
        }
        $revenues = $hotelRepository->getLast3MonthsRevenue($hotel);

        return $this->success($revenues);
    }
}
