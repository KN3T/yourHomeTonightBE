<?php

namespace App\Mapping;

use App\Entity\Hotel;
use App\Entity\HotelImage;
use App\Repository\HotelImageRepository;
use App\Repository\ImageRepository;
use App\Request\Hotel\CreateHotelRequest;
use App\Request\Hotel\PutHotelRequest;

class PutHotelRequestToHotelImages
{
    private ImageRepository $imageRepository;
    private HotelImageRepository $hotelImageRepository;

    public function __construct(ImageRepository $imageRepository, HotelImageRepository $hotelImageRepository)
    {
        $this->imageRepository = $imageRepository;
        $this->hotelImageRepository = $hotelImageRepository;

    }

    public function mapping(PutHotelRequest $putHotelRequest, Hotel $hotel): array
    {
        $imageIDs = $putHotelRequest->getImages();
        $result = [];
        foreach ($imageIDs as $imageID) {
            $image = $this->imageRepository->find($imageID);
            if ($this->hotelImageRepository->findOneBy(['image' => $image])) {
                continue;
            }
            $hotelImage = new HotelImage();
            $hotelImage->setImage($image);
            $hotelImage->setHotel($hotel);
            $result[] = $hotelImage;
        }
        return $result;
    }
}
