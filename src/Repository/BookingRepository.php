<?php

namespace App\Repository;

use App\Entity\Booking;
use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Booking>
 *
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends BaseRepository
{
    public const BOOKING_ALIAS = 'b';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    public function getLatestBooking(Room $room)
    {
        $roomId = $room->getId();
        $bookings = $this->createQueryBuilder(self::BOOKING_ALIAS)
            ->select('b')
            ->where('b.room = :roomId')->setParameter('roomId', $roomId)
            ->orderBy('b.createdAt', 'desc');
        return $bookings->getQuery()->getResult();
    }
}
