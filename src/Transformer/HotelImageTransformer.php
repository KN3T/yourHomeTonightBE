<?php

namespace App\Transformer;

use App\Entity\HotelImage;

class HotelImageTransformer extends BaseTransformer
{
    public const ALLOW = ['id', 'image'];

    public function toArray(HotelImage $hotelImage)
    {
        $result = [];
        $result['imageId'] = $hotelImage->getImage()->getId();
        $result['src'] = $hotelImage->getImage()->getPath();

        return $result;
    }

    public function listToArray($hotelImages): array
    {
        $result = [];
        foreach ($hotelImages as $hotelImage) {
            $result[] = $this->toArray($hotelImage);
        }

        return $result;
    }
}
