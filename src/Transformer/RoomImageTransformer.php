<?php

namespace App\Transformer;

use App\Entity\RoomImage;

class RoomImageTransformer extends BaseTransformer
{
    public const ALLOW = ['id', 'image'];

    public function toArray(RoomImage $roomImage): array
    {
        $result = [];
        $result['imageId'] = $roomImage->getImage()->getId();
        $result['src'] = $roomImage->getImage()->getPath();

        return $result;
    }

    public function listToArray($roomImages): array
    {
        $result = [];
        foreach ($roomImages as $roomImage) {
            $result[] = $this->toArray($roomImage);
        }

        return $result;
    }
}
