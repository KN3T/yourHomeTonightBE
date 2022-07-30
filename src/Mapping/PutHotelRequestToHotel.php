<?php

namespace App\Mapping;

use App\Entity\Hotel;
use App\Repository\HotelImageRepository;
use App\Request\Hotel\PutHotelRequest;

class PutHotelRequestToHotel
{
    private PutHotelRequestToAddress $putHotelRequestToAddress;
    private PutHotelRequestToHotelImages $putHotelRequestToHotelImages;
    private HotelImageRepository $hotelImageRepository;

    public function __construct(
        PutHotelRequestToAddress $putHotelRequestToAddress,
        PutHotelRequestToHotelImages $putHotelRequestToHotelImages,
        HotelImageRepository $hotelImageRepository,
    ) {
        $this->putHotelRequestToAddress = $putHotelRequestToAddress;
        $this->putHotelRequestToHotelImages = $putHotelRequestToHotelImages;
        $this->hotelImageRepository = $hotelImageRepository;
    }

    public function mapping(PutHotelRequest $putHotelRequest, Hotel $hotel): Hotel
    {
        $hotel->setName($putHotelRequest->getName());
        $hotel->setEmail($putHotelRequest->getEmail());
        $hotel->setPhone($putHotelRequest->getPhone());
        $hotel->setRules($putHotelRequest->getRules());
        $hotel->setDescription($putHotelRequest->getDescription());
        $hotel->setUpdatedAt(new \DateTime('now'));
        $this->putHotelRequestToAddress->mapping($putHotelRequest, $hotel);
        $hotelImages = $this->putHotelRequestToHotelImages->mapping($putHotelRequest, $hotel);
        foreach ($hotelImages as $hotelImage) {
            $this->hotelImageRepository->save($hotelImage);
            $hotel->addHotelImage($hotelImage);
        }

        return $hotel;
    }
}
