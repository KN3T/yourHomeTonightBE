<?php

namespace App\Transformer;

class HotelRevenueTransformer extends BaseTransformer
{
    public function toArray(array $hotelRevenue): array
    {
        $result = [];
        $result['roomId'] = $hotelRevenue['roomId'];
        $result['roomNumber'] = $hotelRevenue['roomNumber'];
        $result['revenue'] = $hotelRevenue['revenue'];

        return $result;
    }

    public function listToArray(array $hotelRevenues): array
    {
        $result = [];
        $result['total'] = count($hotelRevenues);
        foreach ($hotelRevenues as $hotelRevenue) {
            $result['revenues'][] = $this->toArray($hotelRevenue);
        }

        return $result;
    }
}
