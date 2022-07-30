<?php

namespace App\Mapping;

use App\Entity\Rating;
use App\Request\Rating\CreateRatingRequest;

class CreateRatingRequestRatingMapper
{
    public function mapping(CreateRatingRequest $createRatingRequest): Rating
    {
        $rating = new Rating();
        $rating->setContent($createRatingRequest->getContent());
        $rating->setRating($createRatingRequest->getRating());

        return $rating;
    }
}
