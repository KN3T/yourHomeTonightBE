<?php

namespace App\Tests\Service;

use App\Entity\Booking;
use App\Entity\Hotel;
use App\Entity\HotelImage;
use App\Entity\Image;
use App\Entity\Room;
use App\Service\MailerService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class MailerServiceTest extends TestCase
{
    public function testSendEmail()
    {
        $parameterBagMock = $this->getMockBuilder(ParameterBag::class)
            ->disableOriginalConstructor()
            ->getMock();
        $parameterBagMock->expects($this->once())
            ->method('get')
            ->willReturn([
                'name' => 'admin',
                'username' => 'admin',
                'password' => 'admin',
                'smtp' => [
                    'host' => 'smtp.gmail.com',
                    'port' => 587,
                ]
            ]);
        $mailerService = new MailerService($parameterBagMock, __DIR__ . '/../..');
        $now = new \DateTime();
        $booking = new Booking();
        $room = new Room();
        $hotel = new Hotel();
        $hotelImage = new HotelImage();
        $hotelImage->setImage(new Image());
        $hotel->addHotelImage($hotelImage);
        $room->setHotel($hotel);
        $booking->setRoom($room);
        $booking->setCheckIn($now);
        $booking->setCheckOut($now);
        $mailerService->send($booking);
    }
}
