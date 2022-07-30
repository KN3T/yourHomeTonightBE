<?php

namespace App\Tests\Request\Booking;

use App\Request\Booking\CreateBookingRequest;
use App\Traits\DateTimeTraits;
use PHPUnit\Framework\TestCase;

class CreateBookingRequestTest extends TestCase
{
    use DateTimeTraits;

    public function testGetFullName()
    {
        $createBookingRequest = new CreateBookingRequest();
        $createBookingRequest->setFullName('Peter');
        $result = $createBookingRequest->getFullName();

        $this->assertEquals('Peter', $result);
    }

    public function testGetEmail()
    {
        $createBookingRequest = new CreateBookingRequest();
        $createBookingRequest->setEmail('Peter@gg.com');
        $result = $createBookingRequest->getEmail();

        $this->assertEquals('Peter@gg.com', $result);
    }

    public function testGetPhone()
    {
        $createBookingRequest = new CreateBookingRequest();
        $createBookingRequest->setPhone('0948474847');
        $result = $createBookingRequest->getPhone();

        $this->assertEquals('0948474847', $result);
    }

    public function testGetCheckIn()
    {
        $createBookingRequest = new CreateBookingRequest();
        $createBookingRequest->setCheckIn(1657182697);
        $result = $createBookingRequest->getCheckIn();
        $expected = $this->timestampToDateTime(1657182697);
        $this->assertEquals($expected, $result);
    }

    public function testGetCheckout()
    {
        $createBookingRequest = new CreateBookingRequest();
        $createBookingRequest->setCheckout(1657182697);
        $result = $createBookingRequest->getCheckout();
        $expected = $this->timestampToDateTime(1657182697);
        $this->assertEquals($expected, $result);
    }

    public function testGetUserId()
    {
        $createBookingRequest = new CreateBookingRequest();
        $createBookingRequest->setUserId(2);
        $result = $createBookingRequest->getUserId();
        $this->assertEquals(2, $result);
    }

    public function testGetRoomId()
    {
        $createBookingRequest = new CreateBookingRequest();
        $createBookingRequest->setRoomId(2);
        $result = $createBookingRequest->getRoomId();
        $this->assertEquals(2, $result);
    }
}
