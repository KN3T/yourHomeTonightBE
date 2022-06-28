<?php

namespace App\Transformer;

use App\Entity\Hotel;

class HotelTransformer extends BaseTransformer
{
    public const ALLOW = ['id', 'name', 'description', 'phone', 'email','rules'];
    private AddressTransformer $addressTransformer;

    public function __construct(AddressTransformer $addressTransformer)
    {
        $this->addressTransformer = $addressTransformer;
    }

    public function toArray(Hotel $hotel): array
    {
        $result =  $this->transform($hotel, static::ALLOW);
        $result['address'] = $this->addressTransformer->toArray($hotel->getAddress());
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
