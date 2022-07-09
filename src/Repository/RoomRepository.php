<?php

namespace App\Repository;

use App\Entity\Booking;
use App\Entity\Hotel;
use App\Entity\Rating;
use App\Entity\Room;
use App\Request\Booking\CreateBookingRequest;
use App\Request\Room\ListRoomRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Room>
 *
 * @method Room|null find($id, $lockMode = null, $lockVersion = null)
 * @method Room|null findOneBy(array $criteria, array $orderBy = null)
 * @method Room[]    findAll()
 * @method Room[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRepository extends BaseRepository
{
    public const ROOM_ALIAS = 'r';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class, self::ROOM_ALIAS);
    }

    public function list(Hotel $hotel, ListRoomRequest $roomRequest)
    {
        $roomsNotAvailableIds = $this->getNotAvailableRoomsIds($roomRequest);

        $rooms = $this->createQueryBuilder(static::ROOM_ALIAS)
            ->select('r, SUM(ra.rating)/count(ra.rating)  as rating')
            ->join(Hotel::class, 'h', Join::WITH, 'h.id=r.hotel')
            ->leftJoin(Booking::class, 'b', Join::WITH, 'r.id=b.room')
            ->leftJoin(Rating::class, 'ra', Join::WITH, 'ra.booking = b.id')
            ->groupBy('r.id');
        $rooms = $this->filterByRating($rooms, $roomRequest->getRating());
        $rooms->where('1=1');


        $rooms = $this->filterByPrice($rooms, $roomRequest->getMinPrice(), $roomRequest->getMaxPrice());
        $rooms = $this->andFilter($rooms, 'beds', $roomRequest->getBeds());
        $rooms = $this->andFilter($rooms, 'type', $roomRequest->getType());

        $rooms = $this->filterByPeople($rooms, $roomRequest->getAdults(), $roomRequest->getChildren());
        $rooms->andWhere('h.id=:hotelID')->setParameter('hotelID', $hotel->getId());

        if (count($roomsNotAvailableIds) > 0) {
            $rooms->andWhere($rooms->expr()->notIn('r.id', $roomsNotAvailableIds));
        }

        $rooms = $this->sortBy($rooms, $roomRequest->getSortBy(), $roomRequest->getOrder());
        $rooms->setMaxResults($roomRequest->getLimit())->setFirstResult($roomRequest->getOffset());

        $total = (new Paginator($rooms))->count();
        $rooms = $rooms->getQuery()->getResult();
        $rooms['total'] = $total;

        return $rooms;
    }

    private function filterByPrice(QueryBuilder $rooms, ?float $minPrice, ?float $maxPrice): QueryBuilder
    {
        if (null == $minPrice || null == $maxPrice) {
            return $rooms;
        }

        return $rooms->andWhere('r.price >= :minPrice')
            ->setParameter('minPrice', $minPrice)
            ->andWhere('r.price <= :maxPrice')
            ->setParameter('maxPrice', $maxPrice);
    }

    /**
     * @param ListRoomRequest $roomRequest
     * @return array
     */
    public function getNotAvailableRoomsIds(ListRoomRequest $roomRequest): array
    {
        $roomsNotAvailable = $this->createQueryBuilder(static::ROOM_ALIAS)
            ->select('r.id as roomId')
            ->join(Booking::class, 'b', Join::WITH, 'r.id=b.room')
            ->where('b.checkIn <= :checkOut')
            ->andWhere('b.checkOut >= :checkIn')
            ->setParameter('checkIn', $roomRequest->getCheckIn())
            ->setParameter('checkOut', $roomRequest->getCheckOut());

        $roomsNotAvailable->andWhere($roomsNotAvailable->expr()->orX(
            $roomsNotAvailable->expr()->eq('b.status', Booking::PENDING),
            $roomsNotAvailable->expr()->eq('b.status', Booking::SUCCESS),
            $roomsNotAvailable->expr()->isNull('b.status'),
        ));
        $roomsNotAvailableArray = $roomsNotAvailable->getQuery()->getResult();
        return array_column($roomsNotAvailableArray, 'roomId');
    }

    private function filterByDate(QueryBuilder $rooms, ?\DateTime $checkIn, ?\DateTime $checkOut): QueryBuilder
    {
        return $rooms->andWhere($rooms->expr()->orX(
            $rooms->expr()->gt('b.checkIn', ':checkOut'),
            $rooms->expr()->lt('b.checkOut', ':checkIn'),
            $rooms->expr()->isNull('b.checkIn'),
        ))
            ->setParameter('checkIn', $checkIn)
            ->setParameter('checkOut', $checkOut);
    }

    private function filterByPeople(QueryBuilder $rooms, ?int $adults, ?int $children): QueryBuilder
    {
        return $rooms->andWhere($rooms->expr()->andX(
            $rooms->expr()->gte('r.adults', ':adults'),
            $rooms->expr()->gte('r.children', ':children')
        ))
            ->setParameter('adults', $adults)
            ->setParameter('children', $children);
    }

    public function checkRoomAvailable(CreateBookingRequest $createBookingRequest): bool
    {
        $room = $this->createQueryBuilder(static::ROOM_ALIAS)
            ->join(Booking::class, 'b', Join::WITH, 'r.id=b.room')
            ->where('r.id=:roomId')
            ->andWhere('b.checkIn <= :checkOut')
            ->andWhere('b.checkOut >= :checkIn')
            ->setParameter('roomId', $createBookingRequest->getRoomId())
            ->setParameter('checkIn', $createBookingRequest->getCheckIn())
            ->setParameter('checkOut', $createBookingRequest->getCheckOut());
        $room->andWhere($room->expr()->orX(
            $room->expr()->eq('b.status', Booking::PENDING),
            $room->expr()->eq('b.status', Booking::SUCCESS),
            $room->expr()->isNull('b.status'),
        ));
        $roomCount = (new Paginator($room))->count();
        if (0 === $roomCount) {
            return true;
        }

        return false;
    }

    public function getMinAndMaxPrice(): array
    {
        return $this->createQueryBuilder(static::ROOM_ALIAS)
            ->select('min(r.price) as minPrice, max(r.price) as maxPrice')
            ->getQuery()->getResult();
    }

    public function getDetails(Room $room)
    {
        $rooms = $this->createQueryBuilder(static::ROOM_ALIAS)
            ->select('r, SUM(ra.rating)/count(ra.rating)  as rating')
            ->join(Hotel::class, 'h', Join::WITH, 'h.id=r.hotel')
            ->leftJoin(Booking::class, 'b', Join::WITH, 'r.id=b.room')
            ->leftJoin(Rating::class, 'ra', Join::WITH, 'ra.booking = b.id')
            ->where('r.id=:roomId')->setParameter('roomId', $room->getId())
            ->groupBy('r.id');

        return $rooms->getQuery()->getOneOrNullResult();
    }

    private function filterByRating(QueryBuilder $rooms, $rating): QueryBuilder
    {
        if (null === $rating) {
            return $rooms;
        }

        return $rooms->having('rating > :rating')
            ->andHaving('rating < :ratingBias')
            ->setParameter('rating', $rating - 0.25)
            ->setParameter('ratingBias', $rating + 0.25);
    }

    public function listBookings(Room $room)
    {
        $ratings = $this->createQueryBuilder(static::ROOM_ALIAS)
            ->select('b as booking')
            ->join(Booking::class, 'b', Join::WITH, 'b.room = r.id')
            ->where('r.id = :roomId')->setParameter('roomId', $room->getId());

        return $ratings->getQuery()->getResult();
    }

    public function getYearlyRevenue(Room $room)
    {
        $revenue = $this->createQueryBuilder(static::ROOM_ALIAS)
            ->select('SUM(b.total) as revenue, r.number as RoomNumber')
            ->join(Booking::class, 'b', Join::WITH, 'b.room = r.id')
            ->where('r.id = :roomId')->setParameter('roomId', $room->getId())
            ->andWhere('b.status = :status')->setParameter('status', Booking::DONE)
            ->andWhere('b.checkOut >= :year')->setParameter('year', new \DateTime('first day of January this year'));

        return $revenue->getQuery()->getOneOrNullResult();
    }

    public function getLastYearRevenue(Room $room)
    {
        $revenue = $this->createQueryBuilder(static::ROOM_ALIAS)
            ->select('SUM(b.total) as revenue, r.number as RoomNumber')
            ->join(Booking::class, 'b', Join::WITH, 'b.room = r.id')
            ->where('r.id = :roomId')->setParameter('roomId', $room->getId())
            ->andWhere('b.status = :status')->setParameter('status', Booking::DONE)
            ->andWhere('b.checkOut >= :year')->setParameter('year', new \DateTime('first day of January last year'));

        return $revenue->getQuery()->getOneOrNullResult();
    }
}
