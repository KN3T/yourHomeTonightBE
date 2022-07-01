<?php

namespace App\Service;

use App\Entity\Hotel;
use App\Entity\Room;
use App\Mapping\CreateRoomRequestMapper;
use App\Repository\RoomRepository;
use App\Request\Room\CreateRoomRequest;
use App\Request\Room\ListRoomRequest;

class RoomService
{
    private RoomRepository $roomRepository;
    private CreateRoomRequestMapper $createRoomRequestMapper;

    public function __construct(
        RoomRepository $roomRepository,
        CreateRoomRequestMapper $createRoomRequestMapper,
    ) {
        $this->roomRepository = $roomRepository;
        $this->createRoomRequestMapper = $createRoomRequestMapper;
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
}
