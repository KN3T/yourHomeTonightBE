<?php

namespace App\Mapping;

use App\Entity\Room;
use App\Entity\RoomImage;
use App\Repository\ImageRepository;
use App\Request\Room\CreateRoomRequest;

class CreateRoomRequestRoomImageMapper
{
    private ImageRepository $imageRepository;

    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    public function mapping(CreateRoomRequest $createRoomRequest, Room $room): array
    {
        $imageIDs = $createRoomRequest->getImages();
        $result = [];
        foreach ($imageIDs as $imageID) {
            $image = $this->imageRepository->find($imageID);
            $roomImage = new RoomImage();
            $roomImage->setImage($image);
            $roomImage->setRoom($room);
            $result[] = $roomImage;
        }

        return $result;
    }
}
