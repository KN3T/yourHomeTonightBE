<?php

namespace App\Transformer;

use App\Entity\Booking;
use App\Repository\UserRepository;

class BookingTransformer extends BaseTransformer
{
    private UserTransformer $userTransformer;
    private DetailRoomTransformer $roomTransformer;

    public function __construct(DetailRoomTransformer $roomTransformer, UserTransformer $userTransformer)
    {
        $this->userTransformer = $userTransformer;
        $this->roomTransformer = $roomTransformer;
    }

    public const ALLOW = ['id', 'fullName', 'phone', 'email', 'checkIn', 'checkOut', 'status', 'createdAt'];

    public function toArray(Booking $booking): array
    {
        $result = $this->transform($booking, static::ALLOW);
        $result['user'] = $this->userTransformer->toArray($booking->getUser());
        $result['room'] = $this->roomTransformer->toArray($booking->getRoom());

        return $result;
    }
}
