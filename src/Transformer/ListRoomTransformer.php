<?php

namespace App\Transformer;

use App\Entity\Room;

class ListRoomTransformer extends BaseTransformer
{
    public const ALLOW = ['id', 'number', 'type', 'beds', 'price', 'adults', 'children', 'asset', 'description', ''];

    private RoomImageTransformer $roomImageTransformer;

    public function __construct(RoomImageTransformer $roomImageTransformer)
    {
        $this->roomImageTransformer = $roomImageTransformer;
    }

    public function toArray(Room $room): array
    {
        $result = $this->transform($room, static::ALLOW);
        $result['images'] = $this->roomImageTransformer->listToArray($room->getRoomImages());

        return $result;
    }

    public function listToArray(array $rooms): array
    {
        $result = [];
        $result['total'] = $rooms['total'];
        unset($rooms['total']);
        foreach ($rooms as $room) {
            $result['rooms'][] = $this->toArray($room);
        }

        return $result;
    }
}
