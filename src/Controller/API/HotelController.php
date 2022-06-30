<?php

namespace App\Controller\API;

use App\Entity\Hotel;
use App\Repository\HotelRepository;
use App\Request\Hotel\ListHotelRequest;
use App\Service\HotelService;
use App\Traits\JsonResponseTrait;
use App\Transformer\ListHotelTransformer;
use App\Transformer\ValidatorTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Hotel $hotel, HotelRepository $hotelRepository): JsonResponse
    {
        $hotelRepository->remove($hotel);

        return $this->success([], Response::HTTP_NO_CONTENT);
    }
}
