<?php

namespace App\Transformer;

use App\Entity\Hotel;

class HotelTransformer extends BaseTransformer
{
    public const ALLOW = ['id', 'name', 'description', 'phone', 'email', 'rules'];
    private AddressTransformer $addressTransformer;
    private HotelImageTransformer $hotelImageTransformer;

    public function __construct(AddressTransformer $addressTransformer, HotelImageTransformer $hotelImageTransformer)
    {
        $this->addressTransformer = $addressTransformer;
        $this->hotelImageTransformer = $hotelImageTransformer;
    }

    public function toArray(Hotel $hotel): array
    {
        $result = $this->transform($hotel, static::ALLOW);
        $result['address'] = $this->addressTransformer->toArray($hotel->getAddress());
        $result['images'] = $this->hotelImageTransformer->listToArray($hotel->getHotelImages());

        return $result;
    }

    public function listToArray(array $hotels): array
    {
        $result = [];
        foreach ($hotels as $hotel) {
            $result[] = $this->toArray($hotel);
        }

        return $result;
    }
}
