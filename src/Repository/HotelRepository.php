<?php

namespace App\Repository;

use App\Entity\Address;
use App\Entity\Booking;
use App\Entity\Hotel;
use App\Entity\Rating;
use App\Entity\Room;
use App\Request\Hotel\ListHotelRequest;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @extends ServiceEntityRepository<Hotel>
 *
 * @method Hotel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hotel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hotel[]    findAll()
 * @method Hotel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HotelRepository extends BaseRepository
{
    public const HOTEL_ALIAS = 'h';
    public const MONTHS = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December',
    ];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hotel::class, static::HOTEL_ALIAS);
    }

    public function list(ListHotelRequest $hotelRequest): array
    {
        $hotels = $this->createQueryBuilder(static::HOTEL_ALIAS)
            ->select('h, MIN(r.price) AS price, SUM(ra.rating)/count(ra.rating)  as rating, COUNT(ra.rating) as ratingCount')
            ->join(Address::class, 'ad', Join::WITH, 'ad.hotel = h.id')
            ->join(Room::class, 'r', Join::WITH, 'r.hotel = h.id')
            ->leftJoin(Booking::class, 'b', Join::WITH, 'b.room = r.id')
            ->leftJoin(Rating::class, 'ra', Join::WITH, 'ra.booking = b.id')
            ->groupBy('r.hotel');
        $hotels = $this->filterByPrice($hotels, $hotelRequest->getMinPrice(), $hotelRequest->getMaxPrice());
        $hotels = $this->filterByRating($hotels, $hotelRequest->getRating());
        $hotels->where('1=1');
        $hotels = $this->filterByDate($hotels, $hotelRequest->getCheckIn(), $hotelRequest->getCheckOut());

        $hotels->orWhere($hotels->expr()->orX(
            $hotels->expr()->eq('b.status', Booking::CANCELLED),
            $hotels->expr()->eq('b.status', Booking::DONE),
            $hotels->expr()->isNull('b.status'),
        ));

        $hotels->orWhere($hotels->expr()->orX(
            $hotels->expr()->eq('b.status', Booking::CANCELLED),
            $hotels->expr()->eq('b.status', Booking::DONE),
            $hotels->expr()->isNull('b.status'),
        ));
        $hotels = $this->filterByCity($hotels, $hotelRequest->getCity());
        $hotels = $this->filterByPeople($hotels, $hotelRequest->getAdults(), $hotelRequest->getChildren());
        $hotels = $this->filterByBeds($hotels, $hotelRequest->getBeds());
        $hotels = $this->orderByPrice($hotels, $hotelRequest);

        $hotels->setMaxResults($hotelRequest->getLimit())->setFirstResult($hotelRequest->getOffset());
        $total = (new Paginator($hotels))->count();
        $hotels = $hotels->getQuery()->getResult();
        $hotels['total'] = $total;

        return $hotels;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function detail(Hotel $hotel)
    {
        $hotels = $this->createQueryBuilder(static::HOTEL_ALIAS)
            ->select('h, MIN(r.price) AS price, (SUM(ra.rating)/count(ra.rating))  as rating, COUNT(ra.rating) as ratingCount')
            ->where('h.id=:hotelId')
            ->setParameter('hotelId', $hotel->getId())
            ->join(Room::class, 'r', Join::WITH, 'r.hotel = h.id')
            ->leftJoin(Booking::class, 'b', Join::WITH, 'b.room = r.id')
            ->leftJoin(Rating::class, 'ra', Join::WITH, 'ra.booking = b.id')
            ->groupBy('r.hotel');

        return $hotels->getQuery()->getOneOrNullResult();
    }

    public function listRatings(Hotel $hotel)
    {
        $ratings = $this->createQueryBuilder(static::HOTEL_ALIAS)
            ->select('r.number as roomNumber, ra as review, r.type as roomType')
            ->join(Room::class, 'r', Join::WITH, 'r.hotel = h.id')
            ->join(Booking::class, 'b', Join::WITH, 'b.room = r.id')
            ->join(Rating::class, 'ra', Join::WITH, 'ra.booking = b.id')
            ->where('h.id = :hotelId')->setParameter('hotelId', $hotel->getId());

        return $ratings->getQuery()->getResult();
    }

    public function listBookings(Hotel $hotel)
    {
        $ratings = $this->createQueryBuilder(static::HOTEL_ALIAS)
            ->select('b as booking')
            ->join(Room::class, 'r', Join::WITH, 'r.hotel = h.id')
            ->join(Booking::class, 'b', Join::WITH, 'b.room = r.id')
            ->where('h.id = :hotelId')->setParameter('hotelId', $hotel->getId());

        return $ratings->getQuery()->getResult();
    }

    /**
     * @throws Exception
     */
    public function getYearlyRevenue(Hotel $hotel): array
    {
        $result = [];
        foreach (self::MONTHS as $month) {
            $startDate = new \DateTimeImmutable('first day of '.$month);
            $endDate = $startDate->modify('last day of this month')->setTime(23, 59, 59);
            $revenue = $this->createQueryBuilder(static::HOTEL_ALIAS)
                ->select('SUM(b.total) as revenue, COUNT(b.id) as totalBookings')
                ->join(Room::class, 'r', Join::WITH, 'r.hotel = h.id')
                ->join(Booking::class, 'b', Join::WITH, 'b.room = r.id')
                ->where('h.id = :hotelId')->setParameter('hotelId', $hotel->getId())
                ->andWhere('b.status = :success OR b.status = :done')
                ->setParameter('success', Booking::SUCCESS)
                ->setParameter('done', Booking::DONE)
                ->andWhere('b.createdAt BETWEEN :startDate AND :endDate')
                ->setParameter('startDate', $startDate)
                ->setParameter('endDate', $endDate)
                ->groupBy('h.id')
            ;
            $result[] = [
                'month' => $month,
                'revenue' => $revenue->getQuery()->getOneOrNullResult()['revenue'] ?? 0,
                'totalBookings' => $revenue->getQuery()->getOneOrNullResult()['totalBookings'] ?? 0,
            ];
        }

        return $result;
    }

    public function getLast3MonthsRevenue(Hotel $hotel): array
    {
        $result = [];
        $date = new \DateTimeImmutable('last day of this month');
        $endDate = $date->modify('last day of this month')->setTime(23, 59, 59);
        $startDate = $date->modify('-3 months')->setTime(0, 0, 0);
        $revenue = $this->createQueryBuilder(static::HOTEL_ALIAS)
            ->select('SUM(b.total) as revenue, COUNT(b.id) as bookings')
            ->join(Room::class, 'r', Join::WITH, 'r.hotel = h.id')
            ->join(Booking::class, 'b', Join::WITH, 'b.room = r.id')
            ->where('h.id = :hotelId')->setParameter('hotelId', $hotel->getId())
            ->andWhere('b.status = :success OR b.status = :done')
            ->setParameter('success', Booking::SUCCESS)
            ->setParameter('done', Booking::DONE)
            ->andWhere('b.createdAt BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->groupBy('h.id')
        ;
        $result[] = [
            'month' => 'last 3 months',
            'totalRevenue' => $revenue->getQuery()->getOneOrNullResult()['revenue'] ?? 0,
            'totalBookings' => $revenue->getQuery()->getOneOrNullResult()['bookings'] ?? 0,
        ];

        return $result;
    }

    private function orderByPrice(QueryBuilder $hotels, ListHotelRequest $hotelRequest): QueryBuilder
    {
        return $hotels
            ->orderBy('price', $hotelRequest->getOrder());
    }

    private function filterByPrice(QueryBuilder $hotels, ?float $minPrice, ?float $maxPrice): QueryBuilder
    {
        if (null === $minPrice || null === $maxPrice) {
            return $hotels;
        }

        return $hotels->having('price >= :minPrice')
            ->setParameter('minPrice', $minPrice)
            ->andHaving('price <= :maxPrice')
            ->setParameter('maxPrice', $maxPrice);
    }

    private function filterByCity(QueryBuilder $hotels, mixed $city): QueryBuilder
    {
        if (null == $city) {
            return $hotels;
        }

        return $hotels->andWhere('ad.city = :city')->setParameter('city', $city);
    }

    private function filterByDate(QueryBuilder $hotels, ?DateTime $checkIn, ?DateTime $checkOut): QueryBuilder
    {
        return $hotels->andWhere($hotels->expr()->orX(
            $hotels->expr()->gt('b.checkIn', ':checkOut'),
            $hotels->expr()->lt('b.checkOut', ':checkIn'),
            $hotels->expr()->isNull('b.checkIn'),
        ))
            ->setParameter('checkIn', $checkIn)
            ->setParameter('checkOut', $checkOut);
    }

    private function filterByPeople(QueryBuilder $hotels, ?int $adults, ?int $children): QueryBuilder
    {
        return $hotels->andWhere($hotels->expr()->andX(
            $hotels->expr()->gte('r.adults', ':adults'),
            $hotels->expr()->gte('r.children', ':children')
        ))
            ->setParameter('adults', $adults)
            ->setParameter('children', $children);
    }

    private function filterByBeds(QueryBuilder $hotels, ?int $beds): QueryBuilder
    {
        return $hotels->andWhere('r.beds >= :beds')
            ->setParameter('beds', $beds);
    }

    private function filterByRating(QueryBuilder $hotels, $rating): QueryBuilder
    {
        if (null === $rating) {
            return $hotels;
        }

        return $hotels->andHaving('rating > :rating')
            ->andHaving('rating < :ratingBias')
            ->setParameter('rating', $rating - 0.25)
            ->setParameter('ratingBias', $rating + 0.25);
    }
}
