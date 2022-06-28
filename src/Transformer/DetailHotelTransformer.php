<?php

namespace App\Transformer;

use App\Entity\Hotel;

class DetailHotelTransformer extends BaseTransformer
{
    public const ALLOW = ['id', 'name', 'description', 'phone', 'email'];

    private AddressTransformer $addressTransformer;
    private HotelImageTransformer $hotelImageTransformer;

    public function __construct(AddressTransformer $addressTransformer, HotelImageTransformer $hotelImageTransformer)
    {
        $this->addressTransformer = $addressTransformer;
        $this->hotelImageTransformer = $hotelImageTransformer;
    }

    public function toArray(Hotel $hotel): array
    {
        $hotelArray = $this->transform($hotel, static::ALLOW);
        $hotelArray['price'] = $hotel['price'];
        $hotelArray['address'] = $this->addressTransformer->toArray($hotelEntity->getAddress());

        $hotelArray['images'] = $this->hotelImageTransformer->listToArray($hotelEntity->getHotelImages());

        return $hotelArray;
    }

}
