<?php

namespace App\Tests\Service;

use App\Entity\Booking;
use App\Entity\Rating;
use App\Mapping\CreateRatingRequestRatingMapper;
use App\Mapping\putRatingRequestRatingMapper;
use App\Repository\RatingRepository;
use App\Request\Rating\CreateRatingRequest;
use App\Request\Rating\PutRatingRequest;
use App\Service\RatingService;
use PHPUnit\Framework\TestCase;

class RatingServiceTest extends TestCase
{
    public function testCreate()
    {
        $ratingRepositoryMock = $this->getMockBuilder(RatingRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $putRatingRequestRatingMapperMock = $this->getMockBuilder(PutRatingRequestRatingMapper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $createRatingRequest = new CreateRatingRequest();
        $createRatingRequest->setContent('content');
        $createRatingRequest->setRating(5);

        $booking = new Booking();
        $createRatingRequestRatingMapper = new CreateRatingRequestRatingMapper();
        $ratingService = new RatingService(
            $ratingRepositoryMock,
            $createRatingRequestRatingMapper,
            $putRatingRequestRatingMapperMock,
        );
        $ratingRepositoryMock->expects($this->once())
            ->method('save')
            ->willReturn(new Rating());
        $rating = $ratingService->create($createRatingRequest, $booking);
        $this->assertInstanceOf(Rating::class, $rating);
    }

    public function testPut()
    {
        $ratingRepositoryMock = $this->getMockBuilder(RatingRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $createRatingRequestRatingMapperMock = $this->getMockBuilder(CreateRatingRequestRatingMapper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $putRatingRequestRatingMapper =  new PutRatingRequestRatingMapper();
        $putRatingRequest = new PutRatingRequest();
        $putRatingRequest->setContent('content');
        $putRatingRequest->setRating(5);
        $rating = new Rating();
        $rating->setContent('content');
        $rating->setRating(5);

        $ratingRepositoryMock->expects($this->once())
            ->method('save')
            ->willReturn($rating);

        $ratingService = new RatingService(
            $ratingRepositoryMock,
            $createRatingRequestRatingMapperMock,
            $putRatingRequestRatingMapper,
        );
        $rating = $ratingService->put($putRatingRequest, $rating);
        $this->assertInstanceOf(Rating::class, $rating);
    }
}
