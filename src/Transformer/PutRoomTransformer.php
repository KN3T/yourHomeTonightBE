<?php

namespace App\Transformer;

use App\Entity\Room;

class PutRoomTransformer extends BaseTransformer
{
    public const ALLOW = [
        'id',
        'hotelId',
        'number',
        'type',
        'price',
        'adults',
        'children',
        'asset',
        'beds',
        'description',
    ];
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
        foreach ($rooms as $room) {
            $result[] = $this->toArray($room);
        }

        return $result;
    }
}
