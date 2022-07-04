<?php

namespace App\Transformer;

class ListHotelRatingsTransformer extends BaseTransformer
{
    public const ALLOW = ['id', 'content', 'rating', 'createdAt', 'updatedAt'];

    public function toArray(array $hotelRating): array
    {
        $ratingEntity = $hotelRating['review'];
        $ratingArray = $this->transform($ratingEntity, ListHotelRatingsTransformer::ALLOW);
        $ratingArray['roomNumber'] = $hotelRating['roomNumber'];
        return $ratingArray;
    }

    public function listToArray(array $hotelRatings): array
    {
        $result = [];
        $result['total'] = count($hotelRatings);
        foreach ($hotelRatings as $hotelRating) {
            $result['reviews'][] = $this->toArray($hotelRating);
        }

        return $result;
    }
}
