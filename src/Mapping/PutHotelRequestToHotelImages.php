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
            $hotelImageExist = $this->hotelImageRepository->findOneBy(['image' => $image]);
            if ($hotelImageExist != null) {
                $result[] = $hotelImageExist;
                continue;
            }
            $hotelImage = new HotelImage();
            $hotelImage->setImage($image);
            $hotelImage->setHotel($hotel);
            $this->hotelImageRepository->save($hotelImage);
            $result[] = $hotelImage;
        }
        $oldHotelImages = $this->hotelImageRepository->findBy(['hotel' => $hotel]);
        $needToDelete = $this->hotelImagesDiff($oldHotelImages, $result);
        if (!empty($needToDelete)) {
            foreach ($needToDelete as $hotelImageId) {
                $hotelImage = $this->hotelImageRepository->find($hotelImageId);
                $this->hotelImageRepository->remove($hotelImage);
            }
        }
        return $result;
    }

    private function hotelImagesDiff(array $hotelImages1, array $hotelImages2)
    {
        $arr1 = [];
        $arr2 = [];
        foreach ($hotelImages1 as $hotelImage) {
            $arr1[] = $hotelImage->getId();
        }
        foreach ($hotelImages2 as $hotelImage) {
            $arr2[] = $hotelImage->getId();
        }
        return array_diff($arr1, $arr2);
    }

}
