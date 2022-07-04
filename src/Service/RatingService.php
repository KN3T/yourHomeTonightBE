<?php

namespace App\Service;

use App\Entity\Booking;
use App\Entity\Rating;
use App\Mapping\CreateRatingRequestRatingMapper;
use App\Mapping\putRatingRequestRatingMapper;
use App\Repository\RatingRepository;
use App\Request\Rating\CreateRatingRequest;
use App\Request\Rating\PutRatingRequest;

class RatingService
{
    private RatingRepository $ratingRepository;
    private CreateRatingRequestRatingMapper $createRatingRequestRatingMapper;
    private PutRatingRequestRatingMapper $putRatingRequestRatingMapper;

    public function __construct(
        RatingRepository $ratingRepository,
        CreateRatingRequestRatingMapper $createRatingRequestRatingMapper,
        PutRatingRequestRatingMapper $putRatingRequestRatingMapper
    ) {
        $this->ratingRepository = $ratingRepository;
        $this->createRatingRequestRatingMapper = $createRatingRequestRatingMapper;
        $this->putRatingRequestRatingMapper = $putRatingRequestRatingMapper;
    }

    public function create(CreateRatingRequest $createRatingRequest, Booking $booking): Rating
    {
        $rating = $this->createRatingRequestRatingMapper->mapping($createRatingRequest);
        $rating->setBooking($booking);
        $this->ratingRepository->save($rating);

        return $rating;
    }

    public function put(PutRatingRequest $putRatingRequest, Rating $rating): Rating
    {
        $this->putRatingRequestRatingMapper->mapping($putRatingRequest, $rating);
        $this->ratingRepository->save($rating);

        return $rating;
    }
}
