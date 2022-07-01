<?php

namespace App\Mapping;

use App\Entity\Booking;
use App\Entity\User;
use App\Repository\RoomRepository;
use App\Request\Booking\CreateBookingRequest;
use Symfony\Component\Security\Core\Security;

class CreateBookingRequestBookingMapper
{
    private RoomRepository $roomRepository;
    private Security $security;

    public function __construct(Security $security, RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
        $this->security = $security;
    }

    public function mapping(CreateBookingRequest $createBookingRequest): Booking
    {
        $booking = new Booking();
        $booking->setFullName($createBookingRequest->getFullName())
            ->setEmail($createBookingRequest->getEmail())
            ->setPhone($createBookingRequest->getPhone())
            ->setCheckIn($createBookingRequest->getCheckIn())
            ->setCheckOut($createBookingRequest->getCheckOut());

        /**
         * @var User $currentUser
         */
        $currentUser = $this->security->getUser();
        $room = $this->roomRepository->find($createBookingRequest->getRoomId());
        $booking->setUser($currentUser)->setRoom($room);
        $room->addBooking($booking);
        $currentUser->addBooking($booking);

        $days = $createBookingRequest->getCheckIn()->diff($createBookingRequest->getCheckOut())->format('%a');
        $booking->setTotal($days * $room->getPrice());

        return $booking;
    }
}
