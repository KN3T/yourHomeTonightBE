<?php

namespace App\Command;

use App\Entity\Booking;
use App\Repository\BookingRepository;
use App\Traits\DateTimeTraits;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:set:booking:expired-status',
    description: 'Set Booking Expired Status',
    aliases: ['app:set:booking:expired-status:command'],
    hidden: false
)]
class SetBookingExpiredStatus extends Command
{
    use DateTimeTraits;
    private BookingRepository $bookingRepository;

    public function __construct(BookingRepository $bookingRepository, string $name = null)
    {
        parent::__construct($name);
        $this->bookingRepository = $bookingRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = new \DateTimeImmutable('now');
        $bookings = $this->bookingRepository->findByStatus(Booking::PENDING);
        foreach ($bookings as $booking) {
            if ($now->getTimestamp() - $booking->getCreatedAt()->getTimestamp() > Booking::TIME_TO_EXPIRED) {
                $output->writeln('Booking id: '.$booking->getId().' is expired');
                $booking->setStatus(Booking::CANCELLED);
                $this->bookingRepository->save($booking);
            }
        }

        return Command::SUCCESS;
    }
}
