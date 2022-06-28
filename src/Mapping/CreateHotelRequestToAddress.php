<?php

namespace App\Mapping;

use App\Entity\Address;
use App\Request\Hotel\CreateHotelRequest;

class CreateHotelRequestToAddress
{
    public function mapping(CreateHotelRequest $createHotelRequest, Address $address): Address
    {
        $address->setCity($createHotelRequest->getCity());
        $address->setProvince($createHotelRequest->getProvince());
        $address->setAddress($createHotelRequest->getAddress());
        return $address;
    }
}
