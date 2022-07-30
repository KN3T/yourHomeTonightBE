<?php

namespace App\Repository;

use App\Entity\HotelImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HotelImage>
 *
 * @method HotelImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method HotelImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method HotelImage[]    findAll()
 * @method HotelImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HotelImageRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HotelImage::class);
    }
}
