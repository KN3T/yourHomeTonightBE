<?php

namespace App\Mapping;

use App\Entity\Hotel;
use App\Entity\HotelImage;
use App\Repository\ImageRepository;
use App\Request\Hotel\CreateHotelRequest;

class CreateHotelRequestToHotelImages
{
    private ImageRepository $imageRepository;

    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    public function mapping(CreateHotelRequest $createHotelRequest, Hotel $hotel): array
    {
        $imageIDs = $createHotelRequest->getImages();
        $result = [];
        foreach ($imageIDs as $imageID) {
            $image = $this->imageRepository->find($imageID);
            $hotelImage = new HotelImage();
            $hotelImage->setImage($image);
            $hotelImage->setHotel($hotel);
            $result[] = $hotelImage;
        }

        return $result;
    }
}
