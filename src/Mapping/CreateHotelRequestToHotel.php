<?php

namespace App\Mapping;

use App\Entity\Address;
use App\Entity\Hotel;
use App\Entity\User;
use App\Request\Hotel\CreateHotelRequest;

class CreateHotelRequestToHotel
{
    public function mapping(CreateHotelRequest $createHotelRequest, User $user, Address $address, Hotel $hotel): Hotel
    {
        $hotel->setName($createHotelRequest->getName());
        $hotel->setEmail($createHotelRequest->getEmail());
        $hotel->setPhone($createHotelRequest->getPhone());
        $hotel->setRules($createHotelRequest->getRules());
        $hotel->setDescription($createHotelRequest->getDescription());
        $hotel->setUser($user);
        $hotel->setAddress($address);
        return $hotel;
    }

}
