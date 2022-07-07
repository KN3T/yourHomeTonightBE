<?php

namespace App\Tests\Entity;

use App\Entity\Booking;
use App\Entity\Rating;
use PHPUnit\Framework\TestCase;

class BookingTest extends TestCase
{
    public function testBooking()
    {
        $booking = new Booking();
        $date = new \DateTimeImmutable('now');
        $rating = new Rating();
        $booking->setCheckIn($date);
        $booking->setCheckOut($date);
        $booking->setCreatedAt($date);
        $booking->setUpdatedAt($date);
        $booking->setDeletedAt($date);
        $booking->setRating($rating);
        $booking->setPurchasedAt($date);
        $booking->setFullName('User');
        $booking->setPhone('123456789');
        $booking->setEmail('email@gg.com');
        $booking->setTotal(100);
        $booking->setStatus(Booking::SUCCESS);
        $this->assertEquals($booking->getCheckIn(), $date);
        $this->assertEquals($booking->getCheckOut(), $date);
        $this->assertEquals($booking->getCreatedAt(), $date);
        $this->assertEquals($booking->getUpdatedAt(), $date);
        $this->assertEquals($booking->getId(), null);
        $this->assertEquals($booking->getRating(), $rating);
        $this->assertEquals($booking->getDeletedAt(), $date);
        $this->assertEquals($booking->getPurchasedAt(), $date);
        $this->assertEquals($booking->getFullName(), 'User');
        $this->assertEquals($booking->getPhone(), '123456789');
        $this->assertEquals($booking->getEmail(), 'email@gg.com');
        $this->assertEquals($booking->getTotal(), 100);
        $this->assertEquals($booking->getStatus(), Booking::SUCCESS);


    }
}
