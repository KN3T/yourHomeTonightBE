<?php

namespace App\Controller\API;

use App\Entity\Hotel;
use App\Entity\User;
use App\Request\Hotel\CreateHotelRequest;
use App\Request\Hotel\PutHotelRequest;
use App\Service\HotelService;
use App\Traits\JsonResponseTrait;
use App\Transformer\HotelTransformer;
use App\Transformer\ValidatorTransformer;
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

    #[Route('/', name: 'list')]
    public function index()
    {
    }

    /**
     * @param CreateHotelRequest $createHotelRequest
     * @param Request $request
     * @param HotelService $hotelService
     * @param HotelTransformer $hotelTransformer
     * @param Security $security
     * @return JsonResponse
     */
    #[Route('', name: 'create', methods: 'POST')]
    public function create(
        CreateHotelRequest $createHotelRequest,
        Request            $request,
        HotelService       $hotelService,
        HotelTransformer   $hotelTransformer,
        Security           $security,
    ): JsonResponse
    {
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
        Hotel            $hotel,
        Request          $request,
        PutHotelRequest  $putHotelRequest,
        HotelTransformer $hotelTransformer,
        Security         $security,
        HotelService     $hotelService,
    ): JsonResponse
    {
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
}
