<?php

namespace App\Mapping;

use App\Entity\Address;
use App\Request\Hotel\PutHotelRequest;

class PutHotelRequestToAddress
{

    public function mapping(PutHotelRequest $putHotelRequest, Address $address): Address
    {
        $address->setCity($putHotelRequest->getCity());
        $address->setProvince($putHotelRequest->getProvince());
        $address->setAddress($putHotelRequest->getAddress());
        return $address;
    }
}
