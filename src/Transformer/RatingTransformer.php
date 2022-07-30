<?php

namespace App\Transformer;

use App\Entity\Rating;

class RatingTransformer extends BaseTransformer
{
    public const ALLOW = ['id', 'content', 'rating', 'createdAt', 'updatedAt'];
    private UserTransformer $userTransformer;

    public function __construct(UserTransformer $userTransformer)
    {
        $this->userTransformer = $userTransformer;
    }

    public function toArray(Rating $rating): array
    {
        $result = $this->transform($rating, static::ALLOW);
        $result['roomId'] = $rating->getBooking()->getRoom()->getId();
        $result['user'] = $this->userTransformer->toArray($rating->getBooking()->getUser());

        return $result;
    }

    public function listToArray(array $ratings): array
    {
        $result = [];
        if (!empty($ratings)) {
            foreach ($ratings as $rating) {
                $result[] = $this->toArray($rating);
            }
        }

        return $result;
    }
}
