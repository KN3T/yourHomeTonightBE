<?php

namespace App\Repository;

use App\Entity\BaseEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class BaseRepository extends ServiceEntityRepository
{
    protected string $alias;

    public function __construct(ManagerRegistry $registry, string $entityClass = '', $alias = '')
    {
        parent::__construct($registry, $entityClass);
        $this->alias = $alias;
    }

    public function save(BaseEntity $entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function remove(BaseEntity $entity)
    {
        $this->getEntityManager()->remove($entity);

        $this->getEntityManager()->flush();
    }

    protected function filter(QueryBuilder $query, string $field, mixed $value): QueryBuilder
    {
        if (empty($value)) {
            return $query;
        }

        return $query->where($this->alias.".$field = :$field")->setParameter($field, $value);
    }

    protected function andFilter(QueryBuilder $query, string $field, mixed $value): QueryBuilder
    {
        if (empty($value)) {
            return $query;
        }

        return $query->andWhere($this->alias.".$field = :$field")->setParameter($field, $value);
    }

    protected function sortBy(QueryBuilder $query, string $sortBy, string $order): QueryBuilder
    {
        if (empty($order) || empty($sortBy)) {
            return $query;
        }

        return $query->orderBy($this->alias.".$sortBy", $order);
    }
}
