<?php

namespace App\Transformer;

use App\Entity\Booking;

class BookingTransformer extends BaseTransformer
{
    private UserTransformer $userTransformer;
    private DetailRoomTransformer $roomTransformer;
    private HotelTransformer $hotelTransformer;
    private RatingTransformer $ratingTransformer;

    public function __construct(
        DetailRoomTransformer $roomTransformer,
        UserTransformer $userTransformer,
        HotelTransformer $hotelTransformer,
        RatingTransformer $ratingTransformer,
    ) {
        $this->userTransformer = $userTransformer;
        $this->roomTransformer = $roomTransformer;
        $this->hotelTransformer = $hotelTransformer;
        $this->ratingTransformer = $ratingTransformer;
    }

    public const ALLOW = ['id', 'fullName', 'phone', 'email', 'checkIn', 'checkOut', 'total', 'status', 'createdAt'];

    public function toArray(Booking $booking): array
    {
        $result = $this->transform($booking, static::ALLOW);
        $result['user'] = $this->userTransformer->toArray($booking->getUser());
        $result['room'] = $this->roomTransformer->toArray($booking->getRoom());
        $result['hotel'] = $this->hotelTransformer->toArray($booking->getRoom()->getHotel());
        if (null !== $booking->getRating()) {
            $result['rating'] = $this->ratingTransformer->toArray($booking->getRating());
        }

        return $result;
    }

    public function listToArray(array $bookings): array
    {
        $result = [];
        if (!empty($bookings)) {
            foreach ($bookings as $booking) {
                $result[] = $this->toArray($booking);
            }
        }

        return $result;
    }
}
