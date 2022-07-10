<?php

namespace App\Tests\Transformer;

use App\Entity\Booking;
use App\Entity\Rating;
use App\Entity\Room;
use App\Entity\User;
use App\Transformer\RatingTransformer;
use App\Transformer\UserTransformer;
use PHPUnit\Framework\TestCase;

class RatingTransformerTest extends TestCase
{
    public function testToArray()
    {
        $userTransformerTest = new UserTransformer();
        $ratingTransformerTest = new RatingTransformer($userTransformerTest);
        $rating = new Rating();
        $booking = new Booking();
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $room = new Room();
        $booking->setUser($user);
        $booking->setRoom($room);
        $rating->setBooking($booking);
        $expected = [
            "id" => null,
            "content" => null,
            "rating" => null,
            "createdAt" => $rating->getCreatedAt(),

            "updatedAt" => $rating->getUpdatedAt(),
            "roomId" => null,
            "user" => $userTransformerTest->toArray($user),
        ];
        $this->assertEquals($expected, $ratingTransformerTest->toArray($rating));
    }

    public function testListToArray()
    {
        $userTransformerTest = new UserTransformer();
        $ratingTransformerTest = new RatingTransformer($userTransformerTest);
        $rating = new Rating();
        $booking = new Booking();
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $room = new Room();
        $booking->setUser($user);
        $booking->setRoom($room);
        $rating->setBooking($booking);
        $expected = [
            [
                "id" => null,
                "content" => null,
                "rating" => null,
                "createdAt" => $rating->getCreatedAt(),

                "updatedAt" => $rating->getUpdatedAt(),
                "roomId" => null,
                "user" => $userTransformerTest->toArray($user),
            ],
        ];
        $this->assertEquals($expected, $ratingTransformerTest->listToArray([$rating]));
    }
}
