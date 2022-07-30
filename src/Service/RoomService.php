<?php

namespace App\Service;

use App\Entity\Hotel;
use App\Entity\Room;
use App\Mapping\CreateRoomRequestMapper;
use App\Mapping\PutRoomRequestRoomMapper;
use App\Repository\BookingRepository;
use App\Repository\RoomRepository;
use App\Request\Room\CreateRoomRequest;
use App\Request\Room\ListRoomRequest;
use App\Request\Room\PutRoomRequest;

class RoomService
{
    private RoomRepository $roomRepository;
    private CreateRoomRequestMapper $createRoomRequestMapper;
    private PutRoomRequestRoomMapper $putRoomRequestRoomMapper;
    private BookingRepository $bookingRepository;

    public function __construct(
        RoomRepository $roomRepository,
        CreateRoomRequestMapper $createRoomRequestMapper,
        PutRoomRequestRoomMapper $putRoomRequestRoomMapper,
        BookingRepository $bookingRepository
    ) {
        $this->roomRepository = $roomRepository;
        $this->createRoomRequestMapper = $createRoomRequestMapper;
        $this->putRoomRequestRoomMapper = $putRoomRequestRoomMapper;
        $this->bookingRepository = $bookingRepository;
    }

    public function create(CreateRoomRequest $createRoomRequest, Hotel $hotel): Room
    {
        $room = new Room();
        $this->createRoomRequestMapper->mapping($createRoomRequest, $room, $hotel);
        $this->roomRepository->save($room);

        return $room;
    }

    public function findAll(Hotel $hotel, ListRoomRequest $roomRequest): array
    {
        return $this->roomRepository->list($hotel, $roomRequest);
    }

    public function put(PutRoomRequest $putRoomRequest, Room $room): Room
    {
        $this->putRoomRequestRoomMapper->mapping($putRoomRequest, $room);
        $this->roomRepository->save($room);

        return $room;
    }
}
