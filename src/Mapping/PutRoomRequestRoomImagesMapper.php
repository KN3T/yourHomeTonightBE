<?php

namespace App\Mapping;

use App\Entity\Room;
use App\Entity\RoomImage;
use App\Repository\ImageRepository;
use App\Repository\RoomImageRepository;
use App\Request\Room\PutRoomRequest;

class PutRoomRequestRoomImagesMapper
{
    private RoomImageRepository $roomImageRepository;
    private ImageRepository     $imageRepository;

    public function __construct(RoomImageRepository $roomImageRepository, ImageRepository $imageRepository)
    {
        $this->roomImageRepository = $roomImageRepository;
        $this->imageRepository = $imageRepository;
    }

    public function mapping(PutRoomRequest $putRoomRequest, Room $room): array
    {
        $imageIDs = $putRoomRequest->getImages();
        $result = [];
        foreach ($imageIDs as $imageID) {
            $image = $this->imageRepository->find($imageID);
            $roomImageExist = $this->roomImageRepository->findOneBy(['image' => $image]);
            if (null != $roomImageExist) {
                $result[] = $roomImageExist;
                continue;
            }
            $roomImage = new RoomImage();
            $roomImage->setImage($image);
            $roomImage->setRoom($room);
            $this->roomImageRepository->save($roomImage);
            $result[] = $roomImage;
        }
        $oldRoomImages = $this->roomImageRepository->findBy(['room' => $room]);
        $needToDelete = $this->roomImagesDiff($oldRoomImages, $result);
        if (!empty($needToDelete)) {
            foreach ($needToDelete as $hotelImageId) {
                $hotelImage = $this->roomImageRepository->find($hotelImageId);
                $this->roomImageRepository->remove($hotelImage);
            }
        }

        return $result;
    }

    private function roomImagesDiff(array $roomImages1, array $roomImages2): array
    {
        $arr1 = [];
        $arr2 = [];
        foreach ($roomImages1 as $roomImage) {
            $arr1[] = $roomImage->getId();
        }
        foreach ($roomImages2 as $roomImage) {
            $arr2[] = $roomImage->getId();
        }

        return array_diff($arr1, $arr2);
    }
}
