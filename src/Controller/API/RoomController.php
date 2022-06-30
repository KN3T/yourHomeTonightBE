<?php

namespace App\Controller\API;

use App\Entity\Hotel;
use App\Request\Room\CreateRoomRequest;
use App\Service\RoomService;
use App\Repository\HotelRepository;
use App\Request\Room\ListRoomRequest;
use App\Traits\JsonResponseTrait;
use App\Transformer\CreateRoomTransformer;
use App\Transformer\ListRoomTransformer;
use App\Transformer\ValidatorTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    #[Route('/hotels/{id}/rooms', name: 'create_rooms')]
    public function create(
        Request           $request,
        RoomService       $roomService,
        CreateRoomRequest $createRoomRequest,
        Hotel             $hotel,
        CreateRoomTransformer $createRoomTransformer,
    ) {
        $request = json_decode($request->getContent(), true);
        $createRoomRequest->fromArray($request);
        $errors = $this->validator->validate($createRoomRequest);
        if (count($errors) > 0) {
            return $this->error($this->validatorTransformer->toArray($errors), Response::HTTP_BAD_REQUEST);
        }
        $room = $roomService->create($createRoomRequest, $hotel);
        $result = $createRoomTransformer->toArray($room);
        return $this->success($result, Response::HTTP_CREATED);
    }

    #[Route('/hotels/{hotelId}/rooms', name: 'list', methods: ['GET'])]
    public function index(
        Request $request,
        $hotelId,
        ListRoomRequest $listRoomRequest,
        RoomService $roomService,
        HotelRepository $hotelRepository,
        ListRoomTransformer $listRoomTransformer
    ): Response {
        $filters = $request->query->all();
        $roomRequest = $listRoomRequest->fromArray($filters);
        $errors = $this->validator->validate($roomRequest);
        if (count($errors) > 0) {
            $errorsTransformer = $this->validatorTransformer->toArray($errors);

            return $this->error($errorsTransformer);
        }
        $hotel = $hotelRepository->find($hotelId);
        $room = $roomService->findAll($hotel, $roomRequest);
        $roomResult = $listRoomTransformer->listToArray($room);

        return $this->success($roomResult);
    }
}
