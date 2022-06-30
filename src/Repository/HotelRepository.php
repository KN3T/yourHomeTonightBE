<?php

namespace App\Repository;

use App\Entity\Address;
use App\Entity\Hotel;
use App\Entity\Room;
use App\Request\Hotel\ListHotelRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

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

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hotel::class, static::HOTEL_ALIAS);
    }

    public function list(ListHotelRequest $hotelRequest): array
    {
        $hotels = $this->createQueryBuilder(static::HOTEL_ALIAS)
            ->select('h, MIN(r.price) AS price')
            ->join(Room::class, 'r', Join::WITH, 'r.hotel = h.id')
            ->join(Address::class, 'ad', Join::WITH, 'ad.hotel = h.id')
            ->groupBy('r.hotel');
        $hotels = $this->filterByPrice($hotels, $hotelRequest->getMinPrice(), $hotelRequest->getMaxPrice());
        $hotels = $this->filterByCity($hotels, $hotelRequest->getCity());
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
            ->select('h, MIN(r.price) AS price')
            ->where('h.id=:hotelId')
            ->setParameter('hotelId', $hotel->getId())
            ->join(Room::class, 'r', Join::WITH, 'r.hotel = h.id')
            ->groupBy('r.hotel');

        return $hotels->getQuery()->getOneOrNullResult();
    }

    private function orderByPrice(QueryBuilder $hotels, ListHotelRequest $hotelRequest): QueryBuilder
    {
        return $hotels
            ->orderBy('price', $hotelRequest->getOrder());
    }

    private function filterByPrice(QueryBuilder $hotels, ?float $minPrice, ?float $maxPrice): QueryBuilder
    {
        if (null == $minPrice || null == $maxPrice) {
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

        return $hotels->where('ad.city = :city')->setParameter('city', $city);
    }
}
