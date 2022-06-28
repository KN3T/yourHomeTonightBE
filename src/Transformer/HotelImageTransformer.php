<?php

namespace App\Transformer;

class HotelImageTransformer extends BaseTransformer
{
    public const ALLOW = ['id', 'image'];

    public function listToArray($hotelImages): array
    {
        $result = [];
        foreach ($hotelImages as $hotelImage) {
            $result[] = $hotelImage->getImage()->getPath();
        }

        return $result;
    }
}
