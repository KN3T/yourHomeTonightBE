<?php

namespace App\Tests\Transformer;

use App\Entity\Booking;
use App\Entity\Hotel;
use App\Entity\Rating;
use App\Entity\Room;
use App\Entity\User;
use App\Transformer\BookingTransformer;
use App\Transformer\DetailRoomTransformer;
use App\Transformer\HotelTransformer;
use App\Transformer\RatingTransformer;
use App\Transformer\UserTransformer;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class BookingTransformerTest extends TestCase
{
    public function testToArray()
    {
        $userTransformerMock = $this->getMockBuilder(UserTransformer::class)->disableOriginalConstructor()->getMock();
        $detailRoomTransformerMock = $this->getMockBuilder(DetailRoomTransformer::class)->disableOriginalConstructor()->getMock();
        $hotelTransformerMock = $this->getMockBuilder(HotelTransformer::class)->disableOriginalConstructor()->getMock();
        $ratingTransformerMock = $this->getMockBuilder(RatingTransformer::class)->disableOriginalConstructor()->getMock();
        $bookingTransformer = new BookingTransformer(
            $detailRoomTransformerMock,
            $userTransformerMock,
            $hotelTransformerMock,
            $ratingTransformerMock
        );
        $booking = new Booking();
        $booking->setFullName('John Doe');
        $booking->setPhone('+123456789');
        $booking->setEmail('user@gg.com');
        $booking->setCheckIn(new DateTimeImmutable('2020-01-01'));
        $booking->setCheckOut(new DateTimeImmutable('2020-01-02'));
        $booking->setTotal(100);

        $hotel = new Hotel();
        $room = new Room();
        $room->setHotel($hotel);
        $booking->setUser($user = new User());
        $booking->setRoom($room);
        $booking->setRating($rating = new Rating());
        $userTransformerMock->expects($this->once())->method('toArray')->with($user)->willReturn(['user' => 'John Doe']);
        $detailRoomTransformerMock->expects($this->once())->method('toArray')->with($room)->willReturn(['room' => 'John Doe']);
        $hotelTransformerMock->expects($this->once())->method('toArray')->with($room->getHotel())->willReturn(['hotel' => 'John Doe']);
        $ratingTransformerMock->expects($this->once())->method('toArray')->with($rating)->willReturn(['rating' => 'John Doe']);
        $result = $bookingTransformer->toArray($booking);
        $now = new DateTimeImmutable();
        $now = $now->format('Y-m-d H:i');
        $result['createdAt'] = $result['createdAt']->format('Y-m-d H:i');
        $this->assertEquals(
            [
                'id' => null,
                'fullName' => 'John Doe',
                'phone' => '+123456789',
                'email' => 'user@gg.com',
                'checkIn' => new DateTimeImmutable('2020-01-01'),
                'checkOut' => new DateTimeImmutable('2020-01-02'),
                'total' => 100.0,
                'status' => Booking::PENDING,
                'user' => ['user' => 'John Doe'],
                'room' => ['room' => 'John Doe'],
                'hotel' => ['hotel' => 'John Doe'],
                'rating' => ['rating' => 'John Doe'],
                'createdAt' => $now,
            ],
            $result
        );
    }

    public function testListToArray()
    {
        $userTransformerMock = $this->getMockBuilder(UserTransformer::class)->disableOriginalConstructor()->getMock();
        $detailRoomTransformerMock = $this->getMockBuilder(DetailRoomTransformer::class)->disableOriginalConstructor()->getMock();
        $hotelTransformerMock = $this->getMockBuilder(HotelTransformer::class)->disableOriginalConstructor()->getMock();
        $ratingTransformerMock = $this->getMockBuilder(RatingTransformer::class)->disableOriginalConstructor()->getMock();
        $bookingTransformer = new BookingTransformer(
            $detailRoomTransformerMock,
            $userTransformerMock,
            $hotelTransformerMock,
            $ratingTransformerMock
        );
        $booking = $this->getBooking();
        $bookings = [
            $booking,
            $booking,
            $booking,
        ];
        $result = $bookingTransformer->listToArray($bookings);
        $this->assertCount(3, $result);
    }

    private function getBooking(): Booking
    {
        $booking = new Booking();
        $booking->setFullName('John Doe');
        $booking->setPhone('+123456789');
        $booking->setEmail('user@gg.com');
        $booking->setCheckIn(new DateTimeImmutable('2020-01-01'));
        $booking->setCheckOut(new DateTimeImmutable('2020-01-02'));
        $booking->setTotal(100);
        $booking->setStatus(Booking::PENDING);
        $hotel = new Hotel();
        $room = new Room();
        $room->setHotel($hotel);
        $booking->setUser(new User());
        $booking->setRoom($room);
        $booking->setRating(new Rating());
        return $booking;
    }
}
