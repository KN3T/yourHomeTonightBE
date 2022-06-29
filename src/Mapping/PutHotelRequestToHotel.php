<?php

namespace App\Mapping;

use App\Entity\Hotel;
use App\Request\Hotel\PutHotelRequest;

class PutHotelRequestToHotel
{
    public function mapping(PutHotelRequest $putHotelRequest, Hotel $hotel): Hotel
    {
        $hotel->setName($putHotelRequest->getName());
        $hotel->setEmail($putHotelRequest->getEmail());
        $hotel->setPhone($putHotelRequest->getPhone());
        $hotel->setRules($putHotelRequest->getRules());
        $hotel->setDescription($putHotelRequest->getDescription());
        return $hotel;
    }
}
