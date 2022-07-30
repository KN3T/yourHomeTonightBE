<?php

namespace App\Transformer;

class ListHotelRatingsTransformer extends BaseTransformer
{
    private UserTransformer $userTransformer;

    public function __construct(UserTransformer $userTransformer)
    {
        $this->userTransformer = $userTransformer;
    }

    public const ALLOW = ['id', 'content', 'rating', 'createdAt', 'updatedAt'];

    public function toArray(array $hotelRating): array
    {
        $ratingEntity = $hotelRating['review'];
        $user = $ratingEntity->getBooking()->getUser();
        $ratingArray = $this->transform($ratingEntity, ListHotelRatingsTransformer::ALLOW);
        $ratingArray['roomNumber'] = $hotelRating['roomNumber'];
        $ratingArray['roomType'] = $hotelRating['roomType'];
        $ratingArray['user'] = $this->userTransformer->toArray($user);

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
