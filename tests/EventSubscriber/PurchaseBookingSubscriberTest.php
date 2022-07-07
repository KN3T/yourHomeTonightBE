<?php

namespace App\Tests\EventSubscriber;

use App\Entity\Booking;
use App\Event\PurchaseBookingEvent;
use App\EventSubscriber\PurchaseBookingSubscriber;
use App\Service\MailerService;
use PHPUnit\Framework\TestCase;

class PurchaseBookingSubscriberTest extends TestCase
{
    public function testGetSubscriberEvents()
    {
        $mailerService = $this->getMockBuilder(MailerService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $subscriber = new PurchaseBookingSubscriber($mailerService);

        $this->assertEquals(
            [
                'email.send' => 'onSendEmail',
            ],
            $subscriber->getSubscribedEvents()
        );
    }

    public function testOnSendEmail()
    {
        $mailerService = $this->getMockBuilder(MailerService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $subscriber = new PurchaseBookingSubscriber($mailerService);

        $booking = new Booking();
        $event = new PurchaseBookingEvent($booking);

        $mailerService->expects($this->once())
            ->method('send')
            ->with($booking);

        $subscriber->onSendEmail($event);
    }
}
