<?php

namespace App\Tests\Service;

use App\Entity\Booking;
use App\Mapping\CreateBookingRequestBookingMapper;
use App\Repository\BookingRepository;
use App\Repository\RoomRepository;
use App\Request\Booking\CreateBookingRequest;
use App\Service\BookingService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class BookingServiceTest extends TestCase
{
    public function testSetBookingDone()
    {
        $bookingRepositoryMock = $this->getMockBuilder(BookingRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $roomRepositoryMock = $this->getMockBuilder(RoomRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $createBookingRequestBookingMapperMock = $this->getMockBuilder(CreateBookingRequestBookingMapper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $bookingService = new BookingService(
            $bookingRepositoryMock,
            $roomRepositoryMock,
            $createBookingRequestBookingMapperMock
        );
        $booking = new Booking();
        $booking->setStatus(Booking::PENDING);
        $bookingService->setBookingDone($booking);
        $this->assertEquals(Booking::DONE, $booking->getStatus());
    }

    public function testCreateBookingSuccess()
    {
        $bookingRepositoryMock = $this->getMockBuilder(BookingRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $roomRepositoryMock = $this->getMockBuilder(RoomRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $createBookingRequestBookingMapperMock = $this->getMockBuilder(CreateBookingRequestBookingMapper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $bookingService = new BookingService(
            $bookingRepositoryMock,
            $roomRepositoryMock,
            $createBookingRequestBookingMapperMock
        );
        $createBookingRequest = $this->getMockBuilder(CreateBookingRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $createBookingRequestBookingMapperMock->expects($this->once())
            ->method('mapping')
            ->willReturn(new Booking());

        $roomRepositoryMock->expects($this->once())
            ->method('checkRoomAvailable')
            ->willReturn(true);
        $booking = $bookingService->createBooking($createBookingRequest);

        $this->assertInstanceOf(Booking::class, $booking);
    }

    public function testCreateBookingFail()
    {
        $bookingRepositoryMock = $this->getMockBuilder(BookingRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $roomRepositoryMock = $this->getMockBuilder(RoomRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $createBookingRequestBookingMapperMock = $this->getMockBuilder(CreateBookingRequestBookingMapper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $bookingService = new BookingService(
            $bookingRepositoryMock,
            $roomRepositoryMock,
            $createBookingRequestBookingMapperMock
        );
        $createBookingRequest = $this->getMockBuilder(CreateBookingRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $createBookingRequestBookingMapperMock->expects($this->once())
            ->method('mapping')
            ->willReturn(new Booking());

        $roomRepositoryMock->expects($this->once())
            ->method('checkRoomAvailable')
            ->willReturn(false);

        $this->expectException(BadRequestException::class);
        $booking = $bookingService->createBooking($createBookingRequest);

    }
}
