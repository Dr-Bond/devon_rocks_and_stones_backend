<?php

namespace App\Repository;

use App\Entity\Location;
use App\Entity\Stone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }

    public function findHiddenStonesByLocation()
    {
        return $this->createQueryBuilder('l')
            ->select('count(s.id) stoneCount')
            ->addSelect('l.area')
            ->join('l.stone','s')
            ->andWhere('s.status = :hidden')
            ->setParameter('hidden',Stone::STATUS_HIDDEN)
            ->groupBy('l.area')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findHiddenStonesLocations()
    {
        return $this->createQueryBuilder('l')
            ->select('l')
            ->addSelect('s')
            ->join('l.stone','s')
            ->andWhere('s.status = :hidden')
            ->setParameter('hidden',Stone::STATUS_HIDDEN)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findFoundStonesByLocation()
    {
        return $this->createQueryBuilder('l')
            ->select('count(s.id) stoneCount')
            ->addSelect('l.area')
            ->join('l.stone','s')
            ->andWhere('s.status = :hidden')
            ->setParameter('hidden',Stone::STATUS_NOT_HIDDEN)
            ->groupBy('l.area')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findPlayerPoints()
    {
        return $this->createQueryBuilder('l')
            ->select('count(l.id) stoneCount')
            ->addSelect('p')
            ->addSelect('u')
            ->join('l.foundBy','p')
            ->join('p.user','u')
            ->where('(l.previousLocation is null and l.kept <> 1)')
            ->getQuery()
            ->getResult()
            ;
    }
}
