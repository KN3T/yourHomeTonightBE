<?php

namespace App\EventSubscriber;

use App\Event\PurchaseBookingEvent;
use App\Service\MailerService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PurchaseBookingSubscriber implements EventSubscriberInterface
{
    private MailerService $mailerService;

    public function __construct(MailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    public static function getSubscribedEvents()
    {
        return [
            'email.send' => 'onSendEmail',
        ];
    }

    public function onSendEmail(PurchaseBookingEvent $event)
    {
        $booking = $event->getBooking();
        $this->mailerService->send($booking);
    }
}
