<?php

namespace App\Mapping;

use App\Entity\Address;
use App\Entity\Hotel;
use App\Request\Hotel\PutHotelRequest;

class PutHotelRequestToAddress
{
    public function mapping(PutHotelRequest $putHotelRequest, Hotel $hotel): Address
    {
        $hotel->getAddress()
        ->setCity($putHotelRequest->getCity())
        ->setProvince($putHotelRequest->getProvince())
        ->setAddress($putHotelRequest->getAddress())
        ->setUpdatedAt(new \DateTime('now'));

        return $hotel->getAddress();
    }
}
