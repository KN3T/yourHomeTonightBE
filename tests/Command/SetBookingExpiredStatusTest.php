<?php

namespace App\Tests\Command;

use App\Command\SetBookingExpiredStatus;
use App\Entity\Booking;
use App\Repository\BookingRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class SetBookingExpiredStatusTest extends TestCase
{

    public function testExecute()
    {
        $bookingRepositoryMock = $this->createMock(BookingRepository::class);
        $command = new SetBookingExpiredStatus($bookingRepositoryMock);
        $booking = new Booking();
        $booking->setCreatedAt(new \DateTimeImmutable('-1 hour'));
        $bookingRepositoryMock->expects($this->once())
            ->method('findByStatus')
            ->willReturn([$booking]);


        $application = new Application();
        $application->add($command);
        $command = $application->find('app:set:booking:expired-status');
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);
        $commandTester->assertCommandIsSuccessful();
    }
}
