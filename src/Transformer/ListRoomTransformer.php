<?php

namespace App\Transformer;

use App\Entity\Room;

class ListRoomTransformer extends BaseTransformer
{
    public const ALLOW = ['id', 'number', 'type', 'beds', 'price', 'adults', 'children', 'asset', 'description', ''];

    public function toArray(Room $room): array
    {
        return $this->transform($room, static::ALLOW);
    }

    public function listToArray(array $rooms): array
    {
        $result = [];
        foreach ($rooms as $room) {
            $result[] = $this->toArray($room);
        }

        return $result;
    }
}
