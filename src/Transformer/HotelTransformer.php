<?php

namespace App\Transformer;

use App\Entity\Hotel;

class HotelTransformer extends BaseTransformer
{
    public const ALLOW = ['id', 'name', 'description', 'phone', 'email'];

    public function toArray(Hotel $hotel): array
    {
        return $this->transform($hotel, static::ALLOW);
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