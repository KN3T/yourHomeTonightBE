<?php

namespace App\Transformer;

class ListRoomBookingsTransformer extends BaseTransformer
{
    public const ALLOW = ['id', 'fullName', 'phone', 'email', 'total', 'status', 'checkIn', 'checkOut', 'createdAt'];

    public function toArray(array $booking): array
    {
        $bookingEntity = $booking['booking'];

        return $this->transform($bookingEntity, static::ALLOW);
    }

    public function listToArray(array $hotelBookings): array
    {
        $result = [];
        $result['total'] = count($hotelBookings);
        foreach ($hotelBookings as $hotelBooking) {
            $result['bookings'][] = $this->toArray($hotelBooking);
        }

        return $result;
    }
}
