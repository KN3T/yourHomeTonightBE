<?php

namespace App\Tests\Transformer;

use App\Entity\Booking;
use App\Transformer\BookingTransformer;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class BookingTransformerTest extends TestCase
{
    /**
     * @var BookingTransformer
     */
    private $bookingTransformer;

    protected function setUp(): void
    {
        $this->bookingTransformer = new BookingTransformer();
    }

    public function testTransform(): void
    {
        $booking = new Booking();
        $date = new DateTimeImmutable('now');
        $bookingTransformer = new BookingTransformer();
    }
}
