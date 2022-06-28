<?php

namespace App\Repository;

use App\Entity\RoomImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RoomImage>
 *
 * @method RoomImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoomImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoomImage[]    findAll()
 * @method RoomImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomImageRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomImage::class);
    }
}
