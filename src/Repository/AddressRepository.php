<?php

namespace App\Repository;

use App\Entity\Address;
use App\Entity\Hotel;
use App\Request\City\ListCityRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Address>
 *
 * @method Address|null find($id, $lockMode = null, $lockVersion = null)
 * @method Address|null findOneBy(array $criteria, array $orderBy = null)
 * @method Address[]    findAll()
 * @method Address[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddressRepository extends BaseRepository
{
    public const ADDRESS_ALIAS = 'ad';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Address::class, static::ADDRESS_ALIAS);
    }

    public function list(ListCityRequest $cityRequest)
    {
        $addressQuery = $this->createQueryBuilder(static::ADDRESS_ALIAS)
            ->select('ad.city, count(h.id) as count_hotel')
            ->join(Hotel::class, 'h', Join::WITH, 'ad.hotel=h.id')
            ->groupBy('ad.city')
            ->orderBy('count_hotel', 'desc');
        $searchCity = $cityRequest->getSearch();
        if (null == $searchCity) {
            return $this->listTopCity($addressQuery, $cityRequest);
        }

        return $this->searchCity($addressQuery, $searchCity);
    }

    private function listTopCity(QueryBuilder $addressQuery, ListCityRequest $cityRequest): array
    {
        return $addressQuery->select('ad.city, count(h.id) as count_hotel')
            ->setMaxResults($cityRequest->getLimit())->setFirstResult($cityRequest->getOffset())
            ->getQuery()->getResult();
    }

    private function searchCity(QueryBuilder $addressQuery, $searchCity)
    {
        return $addressQuery->where('ad.city LIKE :city')->setParameter('city', '%'.$searchCity.'%')
            ->getQuery()->getResult();
    }
}
