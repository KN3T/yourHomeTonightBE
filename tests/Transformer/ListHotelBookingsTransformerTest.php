<?php

namespace App\Tests\Transformer;

use App\Entity\Booking;
use App\Transformer\ListHotelBookingsTransformer;
use PHPUnit\Framework\TestCase;

class ListHotelBookingsTransformerTest extends TestCase
{
    public function testToArray()
    {
        $booking = new Booking();
        $bookingArray = [
            "booking" => $booking
        ];
        $listHotelBookingsTransformer = new ListHotelBookingsTransformer();
        $result = $listHotelBookingsTransformer->toArray($bookingArray);
        $expected = [
            "id" => null,
            "fullName" => null,
            "phone" => null,
            "email" => null,
            "total" => null,
            "status" => 1,
            "checkIn" => null,
            "checkOut" => null,
            "createdAt" => $booking->getCreatedAt(),
        ];
        $this->assertEquals($expected, $result);
    }

    public function testListToArray()
    {
        $booking = new Booking();
        $bookingArray = [
            "booking" => $booking
        ];
        $listHotelBookingsTransformer = new ListHotelBookingsTransformer();
        $result = $listHotelBookingsTransformer->listToArray([$bookingArray]);
        $expected = [
            "total" => 1,
            "bookings" => [
                0 => [
                    "id" => null,
                    "fullName" => null,
                    "phone" => null,
                    "email" => null,
                    "total" => null,
                    "status" => 1,
                    "checkIn" => null,
                    "checkOut" => null,
                    "createdAt" => $booking->getCreatedAt(),
                ],
            ],
        ];
        $this->assertEquals($expected, $result);
    }
}
