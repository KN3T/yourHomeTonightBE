<?php

namespace App\Tests\Event;

use App\Entity\Booking;
use App\Event\PurchaseBookingEvent;
use PHPUnit\Framework\TestCase;

class PurchaseBookingEventTest extends TestCase
{
    public function testGetBooking()
    {
        $booking = new Booking();
        $event = new PurchaseBookingEvent($booking);

        $this->assertEquals($booking, $event->getBooking());
    }
}
