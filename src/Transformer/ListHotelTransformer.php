<?php

namespace App\Transformer;

class ListHotelTransformer extends BaseTransformer
{
    public const ALLOW = ['id', 'name', 'description', 'phone', 'email', 'rules'];

    private AddressTransformer $addressTransformer;
    private HotelImageTransformer $hotelImageTransformer;

    public function __construct(AddressTransformer $addressTransformer, HotelImageTransformer $hotelImageTransformer)
    {
        $this->addressTransformer = $addressTransformer;
        $this->hotelImageTransformer = $hotelImageTransformer;
    }

    public function toArray(array $hotel): array
    {
        $hotelEntity = $hotel[0];
        $hotelArray = $this->transform($hotelEntity, static::ALLOW);
        $hotelArray['price'] = $hotel['price'];
        $hotelArray['rating'] = round($hotel['rating'] * 2 ?? 0) / 2;
        $hotelArray['ratingCount'] = $hotel['ratingCount'];
        $hotelArray['address'] = $this->addressTransformer->toArray($hotelEntity->getAddress());

        $hotelArray['images'] = $this->hotelImageTransformer->listToArray($hotelEntity->getHotelImages());

        return $hotelArray;
    }

    public function listToArray(array $hotels): array
    {
        $result = [];
        $result['total'] = $hotels['total'];
        unset($hotels['total']);
        foreach ($hotels as $hotel) {
            $result['hotels'][] = $this->toArray($hotel);
        }

        return $result;
    }
}
