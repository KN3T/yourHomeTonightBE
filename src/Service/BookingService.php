<?php

namespace App\Service;

use App\Entity\Booking;
use App\Mapping\CreateBookingRequestBookingMapper;
use App\Repository\BookingRepository;
use App\Repository\RoomRepository;
use App\Request\Booking\CreateBookingRequest;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class BookingService
{
    private BookingRepository $bookingRepository;
    private RoomRepository $roomRepository;
    private CreateBookingRequestBookingMapper $createBookingRequestBookingMapper;

    public function __construct(
        BookingRepository $bookingRepository,
        RoomRepository $roomRepository,
        CreateBookingRequestBookingMapper $createBookingRequestBookingMapper
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->roomRepository = $roomRepository;
        $this->createBookingRequestBookingMapper = $createBookingRequestBookingMapper;
    }

    public function createBooking(CreateBookingRequest $createBookingRequest): Booking
    {
        $booking = $this->createBookingRequestBookingMapper->mapping($createBookingRequest);
        $checkRoomAvailable = $this->roomRepository->checkRoomAvailable($createBookingRequest);
        if (!$checkRoomAvailable) {
            throw new BadRequestException('This room is not available');
        }
        $this->bookingRepository->save($booking);

        return $booking;
    }

    public function setBookingDone(Booking $booking): Booking
    {
        $booking->setStatus(Booking::DONE);
        $booking->setUpdatedAt(new \DateTimeImmutable('now'));
        $this->bookingRepository->save($booking);

        return $booking;
    }
}
