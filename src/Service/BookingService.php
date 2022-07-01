<?php

namespace App\Service;

use App\Mapping\CreateBookingRequestBookingMapper;
use App\Repository\BookingRepository;
use App\Request\Booking\CreateBookingRequest;

class BookingService
{
    private BookingRepository $bookingRepository;
    private CreateBookingRequestBookingMapper $createBookingRequestBookingMapper;

    public function __construct(
        BookingRepository $bookingRepository,
        CreateBookingRequestBookingMapper $createBookingRequestBookingMapper
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->createBookingRequestBookingMapper = $createBookingRequestBookingMapper;
    }

    public function createBooking(CreateBookingRequest $createBookingRequest)
    {
        $booking = $this->createBookingRequestBookingMapper->mapping($createBookingRequest);
        $this->bookingRepository->save($booking);

        return $booking;
    }
}
