<?php

namespace App\Controller\API;

use App\Entity\Hotel;
use App\Entity\Room;
use App\Repository\RoomRepository;
use App\Request\Room\CreateRoomRequest;
use App\Request\Room\ListRoomRequest;
use App\Request\Room\PutRoomRequest;
use App\Service\RoomService;
use App\Traits\JsonResponseTrait;
use App\Transformer\CreateRoomTransformer;
use App\Transformer\ListRoomBookingsTransformer;
use App\Transformer\ListRoomTransformer;
use App\Transformer\PutRoomTransformer;
use App\Transformer\ValidatorTransformer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    #[Route('/hotels/{id}/rooms', name: 'list', methods: ['GET'])]
    public function index(
        Request $request,
        Hotel $hotel,
        ListRoomRequest $listRoomRequest,
        RoomService $roomService,
        ListRoomTransformer $listRoomTransformer
    ): Response {
        $filters = $request->query->all();
        $roomRequest = $listRoomRequest->fromArray($filters);
        $errors = $this->validator->validate($roomRequest);
        if (count($errors) > 0) {
            $errorsTransformer = $this->validatorTransformer->toArray($errors);

            return $this->error($errorsTransformer);
        }
        $room = $roomService->findAll($hotel, $roomRequest);
        $roomResult = $listRoomTransformer->listToArray($room);

        return $this->success($roomResult);
    }

    #[Route('/hotels/{id}/rooms', name: 'create_rooms', methods: ['POST'])]
    public function create(
        Request $request,
        RoomService $roomService,
        CreateRoomRequest $createRoomRequest,
        Hotel $hotel,
        CreateRoomTransformer $createRoomTransformer,
    ): JsonResponse {
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

    #[Route('/hotels/{hotelId}/rooms/{id}', name: 'detail', methods: ['GET'])]
    #[Entity('hotel', options: ['id' => 'hotelId'])]
    public function detail(
        Room $room,
        RoomRepository $roomRepository,
        ListRoomTransformer $listRoomTransformer
    ): JsonResponse {
        $room = $roomRepository->getDetails($room);
        $roomArray = $listRoomTransformer->toArray($room);

        return $this->success($roomArray);
    }

    #[Route('/hotels/{hotelId}/rooms/{id}', name: 'delete', methods: ['DELETE'])]
    #[Entity('hotel', options: ['id' => 'hotelId'])]
    public function delete(Room $room, RoomRepository $roomRepository): JsonResponse
    {
        $roomRepository->remove($room);

        return $this->success([], Response::HTTP_NO_CONTENT);
    }

    #[Route('/getPrices', name: 'getPrices', methods: ['GET'])]
    public function getPrices(RoomRepository $roomRepository): JsonResponse
    {
        $minMaxPrice = $roomRepository->getMinAndMaxPrice();

        return $this->success($minMaxPrice[0]);
    }

    #[Route('/hotels/{hotelId}/rooms/{id}', name: 'put_rooms', methods: ['PUT'])]
    #[Entity('hotel', options: ['id' => 'hotelId'])]
    public function put(
        Request $request,
        RoomService $roomService,
        PutRoomRequest $putRoomRequest,
        Hotel $hotel,
        Room $room,
        PutRoomTransformer $putRoomTransformer,
    ): JsonResponse {
        if (!$this->checkRoomInHotel($room, $hotel)) {
            return $this->error('Room not found in Hotel');
        }
        $request = json_decode($request->getContent(), true);
        $putRoomRequest->fromArray($request);
        $errors = $this->validator->validate($putRoomRequest);
        if (count($errors) > 0) {
            return $this->error($this->validatorTransformer->toArray($errors), Response::HTTP_BAD_REQUEST);
        }
        $room = $roomService->put($putRoomRequest, $room);
        $result = $putRoomTransformer->toArray($room);

        return $this->success($result, Response::HTTP_CREATED);
    }

    #[Route('/hotels/{hotelId}/rooms/{id}/bookings', name: 'rooms_list_bookings', methods: 'GET')]
    #[Entity('hotel', options: ['id' => 'hotelId'])]
    #[IsGranted('ROLE_HOTEL')]
    public function listBookings(
        Hotel $hotel,
        Room $room,
        RoomRepository $roomRepository,
        ListRoomBookingsTransformer $bookingTransformer,
    ): JsonResponse {
        if (!$this->checkRoomInHotel($room, $hotel)) {
            return $this->error('Room not found in Hotel');
        }
        $bookings = $roomRepository->listBookings($room);
        $result = $bookingTransformer->listToArray($bookings);

        return $this->success($result);
    }

    #[Route('/hotels/{hotelId}/rooms/{id}/revenue', name: 'rooms_get_revenue', methods: 'GET')]
    #[Entity('hotel', options: ['id' => 'hotelId'])]
    #[IsGranted('ROLE_HOTEL')]
    public function getYearlyRevenue(
        Room $room,
        Hotel $hotel,
        RoomRepository $roomRepository,
    ): JsonResponse {
        if (!$this->checkRoomInHotel($room, $hotel)) {
            return $this->error('Room not found in Hotel');
        }
        $revenue = $roomRepository->getYearlyRevenue($room);

        return $this->success($revenue);
    }

    private function checkRoomInHotel(Room $room, Hotel $hotel): bool
    {
        if ($room->getHotel() === $hotel) {
            return true;
        }

        return false;
    }
}
