<?php

namespace App\Mapping;

use App\Entity\Hotel;
use App\Entity\Room;
use App\Repository\RoomImageRepository;
use App\Request\Room\CreateRoomRequest;

class CreateRoomRequestMapper
{
    private CreateRoomRequestRoomImageMapper $createRoomRequestRoomImageMapper;
    private RoomImageRepository $roomImageRepository;

    public function __construct(
        CreateRoomRequestRoomImageMapper $createRoomRequestRoomImageMapper,
        RoomImageRepository $roomImageRepository,
    ) {
        $this->createRoomRequestRoomImageMapper = $createRoomRequestRoomImageMapper;
        $this->roomImageRepository = $roomImageRepository;
    }

    public function mapping(CreateRoomRequest $createRoomRequest, Room $room, Hotel $hotel): Room
    {
        $room->setNumber($createRoomRequest->getNumber())
            ->setType($createRoomRequest->getType())
            ->setPrice($createRoomRequest->getPrice())
            ->setAdults($createRoomRequest->getAdults())
            ->setChildren($createRoomRequest->getChildren())
            ->setAsset($createRoomRequest->getAsset())
            ->setBeds($createRoomRequest->getBeds())
            ->setDescription($createRoomRequest->getDescription());
        $room->setHotel($hotel);
        $roomImages = $this->createRoomRequestRoomImageMapper->mapping($createRoomRequest, $room);
        foreach ($roomImages as $roomImage) {
            $this->roomImageRepository->save($roomImage);
            $room->addRoomImage($roomImage);
        }

        return $room;
    }
}
