<?php

namespace App\Service;

use App\Entity\Hotel;
use App\Entity\Room;
use App\Mapping\CreateRoomRequestMapper;
use App\Repository\RoomRepository;
use App\Request\Room\CreateRoomRequest;

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
        ;
        $room = new Room();
        $this->createRoomRequestMapper->mapping($createRoomRequest, $room, $hotel);
        $this->roomRepository->save($room);

        return $room;
    }
}
