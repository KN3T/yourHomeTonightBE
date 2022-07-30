<?php

namespace App\Tests\Transformer;

use App\Entity\Booking;
use App\Entity\Rating;
use App\Entity\Room;
use App\Entity\User;
use App\Transformer\ListHotelRatingsTransformer;
use App\Transformer\UserTransformer;
use PHPUnit\Framework\TestCase;

class ListHotelRatingsTransformerTest extends TestCase
{
    public function testToArray()
    {
        $userTransformer = new UserTransformer();
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $listHotelRatingsTransformer = new ListHotelRatingsTransformer($userTransformer);
        $rating = new Rating();
        $booking = new Booking();
        $room = new Room();
        $booking->setRoom($room);
        $booking->setUser($user);
        $rating->setBooking($booking);

        $ratingArray = [
            "review" => $rating,
            "roomNumber" => $booking->getRoom()->getNumber(),
            "roomType" => $booking->getRoom()->getType(),
        ];

        $result = $listHotelRatingsTransformer->toArray($ratingArray);
//        dd($result);
        $expected = [
            "id" => null,
            "content" => null,
            "rating" => null,
            "createdAt" => $rating->getCreatedAt(),
            "updatedAt" => $rating->getUpdatedAt(),
            "roomNumber" => $booking->getRoom()->getNumber(),
            "roomType" => $booking->getRoom()->getType(),
            "user" => [
                "id" => null,
                "email" => null,
                "fullName" => null,
                "phone" => null,
                "role" => "ROLE_USER",
            ],
        ];
        $this->assertEquals($expected, $result);
    }

    public function testListToArray()
    {
        $userTransformer = new UserTransformer();
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $listHotelRatingsTransformer = new ListHotelRatingsTransformer($userTransformer);
        $rating = new Rating();
        $booking = new Booking();
        $room = new Room();
        $booking->setRoom($room);
        $booking->setUser($user);
        $rating->setBooking($booking);

        $ratingArray = [
            [
                "review" => $rating,
                "roomNumber" => $booking->getRoom()->getNumber(),
                "roomType" => $booking->getRoom()->getType(),
            ]
        ];

        $result = $listHotelRatingsTransformer->listToArray($ratingArray);
        $expected = [
            "total" => 1,
            "reviews" => [
                0 => [
                    "id" => null,
                    "content" => null,
                    "rating" => null,
                    "createdAt" => $rating->getCreatedAt(),
                    "updatedAt" => $rating->getUpdatedAt(),
                    "roomNumber" => $booking->getRoom()->getNumber(),
                    "roomType" => $booking->getRoom()->getType(),
                    "user" => [
                        "id" => null,
                        "email" => null,
                        "fullName" => null,
                        "phone" => null,
                        "role" => "ROLE_USER",
                    ],
                ],
            ],
        ];
        $this->assertEquals($expected, $result);
    }
}
