<?php

namespace App\Tests\Transformer;

use App\Entity\Booking;
use App\Transformer\ListRoomBookingsTransformer;
use PHPUnit\Framework\TestCase;

class ListRoomBookingsTransformerTest extends TestCase
{
    public function testToArray()
    {
        $listRoomBookingsTransformer = new ListRoomBookingsTransformer();
        $booking = new Booking();
        $bookingArray = ["booking" => $booking];
        $result = $listRoomBookingsTransformer->toArray($bookingArray);
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
        $listRoomBookingsTransformer = new ListRoomBookingsTransformer();
        $booking = new Booking();
        $bookingArray = ["booking" => $booking];
        $result = $listRoomBookingsTransformer->listToArray([$bookingArray]);
        $expected = [
           "total" => 1,
            "bookings" =>[
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
            ]
        ];
        $this->assertEquals($expected, $result);
    }

}
