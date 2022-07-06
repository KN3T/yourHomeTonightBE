<?php

namespace App\Repository;

use App\Entity\Booking;
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

    // function find bookings by status
    public function findByStatus($status)
    {
        return $this->createQueryBuilder(static::BOOKING_ALIAS)
            ->where(static::BOOKING_ALIAS.'.status = :status')
            ->setParameter('status', $status)
            ->getQuery()
            ->getResult();
    }
}
