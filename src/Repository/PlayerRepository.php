<?php

namespace App\Repository;

use App\Entity\Player;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    public function findPlayerByUser(User $user)
    {
        try {
            return $this->createQueryBuilder('p')
                ->select('p')
                ->addSelect('u')
                ->join('p.user','u')
                ->where('u.id = :user')
                ->setParameter('user',$user->getId())
                ->getQuery()
                ->getOneOrNullResult()
            ;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function findInactivePlayers()
    {
        $date = new \DateTime();
        $date->modify('-14 day');

        return $this->createQueryBuilder('p')
            ->select('p')
            ->addSelect('u')
            ->join('p.user','u')
            ->where('u.lastLogin is not null')
            ->andWhere('u.lastLogin < :past_date')
            ->setParameter('past_date',$date)
            ->getQuery()
            ->getResult()
        ;
    }
}
