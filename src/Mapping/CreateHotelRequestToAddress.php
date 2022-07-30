<?php

namespace App\Mapping;

use App\Entity\Address;
use App\Entity\Hotel;
use App\Repository\HotelImageRepository;
use App\Request\Hotel\CreateHotelRequest;

class CreateHotelRequestToAddress
{
    private HotelImageRepository $hotelImageRepository;

    public function __construct(HotelImageRepository $hotelImageRepository)
    {
        $this->hotelImageRepository = $hotelImageRepository;
    }

    public function mapping(CreateHotelRequest $createHotelRequest, Hotel $hotel): Address
    {
        $address = new Address();
        $address->setCity($createHotelRequest->getCity());
        $address->setProvince($createHotelRequest->getProvince());
        $address->setAddress($createHotelRequest->getAddress());
        $address->setHotel($hotel);

        return $address;
    }
}
