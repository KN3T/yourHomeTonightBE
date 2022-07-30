<?php

namespace App\Mapping;

use App\Entity\Rating;
use App\Request\Rating\PutRatingRequest;

class putRatingRequestRatingMapper
{
    public function mapping(PutRatingRequest $putRatingRequest, Rating $rating): Rating
    {
        $rating->setContent($putRatingRequest->getContent());
        $rating->setRating($putRatingRequest->getRating());
        $rating->setUpdatedAt(new \DateTime('now'));

        return $rating;
    }
}
