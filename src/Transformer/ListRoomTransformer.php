<?php

namespace App\Transformer;

class ListRoomTransformer extends BaseTransformer
{
    public const ALLOW = ['id', 'number', 'type', 'beds', 'price', 'adults', 'children', 'asset', 'description', ''];

    private RoomImageTransformer $roomImageTransformer;

    public function __construct(RoomImageTransformer $roomImageTransformer)
    {
        $this->roomImageTransformer = $roomImageTransformer;
    }

    public function toArray(array $room): array
    {
        $roomEntity = $room[0];
        $roomArray = $this->transform($roomEntity, static::ALLOW);
        $roomArray['rating'] = round($room['rating'] * 2 ?? 0) / 2;
        $roomArray['images'] = $this->roomImageTransformer->listToArray($roomEntity->getRoomImages());

        return $roomArray;
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
