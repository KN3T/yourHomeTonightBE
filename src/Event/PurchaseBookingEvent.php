<?php

namespace App\Event;

use App\Entity\Booking;
use Symfony\Contracts\EventDispatcher\Event;

class PurchaseBookingEvent extends Event
{
    public const SENDMAIL = 'email.send';

    /**
     * @var Booking
     */
    public $booking;

    /**
     * CoffeeEvent constructor.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function getBooking(): Booking
    {
        return $this->booking;
    }
}
