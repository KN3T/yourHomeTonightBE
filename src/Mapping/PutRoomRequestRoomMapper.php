<?php

namespace App\Mapping;

use App\Entity\Room;
use App\Repository\RoomImageRepository;
use App\Request\Room\PutRoomRequest;

class PutRoomRequestRoomMapper
{
    private PutRoomRequestRoomImagesMapper $putRoomRequestRoomImagesMapper;
    private RoomImageRepository $roomImageRepository;

    public function __construct(PutRoomRequestRoomImagesMapper $putRoomRequestRoomImagesMapper, RoomImageRepository $roomImageRepository)
    {
        $this->roomImageRepository = $roomImageRepository;
        $this->putRoomRequestRoomImagesMapper = $putRoomRequestRoomImagesMapper;
    }

    public function mapping(PutRoomRequest $putRoomRequest, Room $room)
    {
        $room->setUpdatedAt(new \DateTime('now'));
        $room->setNumber($putRoomRequest->getNumber());
        $room->setType($putRoomRequest->getType());
        $room->setPrice($putRoomRequest->getPrice());
        $room->setAdults($putRoomRequest->getAdults());
        $room->setChildren($putRoomRequest->getChildren());
        $room->setAsset($putRoomRequest->getAsset());
        $room->setBeds($putRoomRequest->getBeds());
        $room->setDescription($putRoomRequest->getDescription());

        $roomImages = $this->putRoomRequestRoomImagesMapper->mapping($putRoomRequest, $room);
        foreach ($roomImages as $roomImage) {
            $this->roomImageRepository->save($roomImage);
            $room->addRoomImage($roomImage);
        }

        return $room;
    }
}
