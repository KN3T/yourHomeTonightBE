<?php

namespace App\Repository;

use App\Entity\Hotel;
use App\Entity\Room;
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
        $rooms = $this->createQueryBuilder(static::ROOM_ALIAS)
            ->join(Hotel::class, 'h', Join::WITH, 'h.id=r.hotel')
            ->where('h.id=:hotelID')->setParameter('hotelID', $hotel->getId());
        $rooms = $this->andFilter($rooms, 'beds', $roomRequest->getBeds());
        $rooms = $this->andFilter($rooms, 'type', $roomRequest->getType());
        $rooms = $this->filterByPrice($rooms, $roomRequest->getMinPrice(), $roomRequest->getMaxPrice());
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
}
