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
            ->andWhere('u.inactiveEmailSentOn < :past_date')
            ->setParameter('past_date',$date)
            ->getQuery()
            ->getResult()
        ;
    }

    public function loginActivity()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT
                    w.*,
                    CASE 
                        WHEN current_week - week_number = 0 THEN 'This Week'
                        WHEN current_week - week_number = 1 THEN '1 Week Ago'
                        ELSE CONCAT(CONVERT(current_week - week_number,CHAR), ' Weeks Ago')
                    END number_of_weeks
                FROM
                    (SELECT
                       COUNT(WEEKOFYEAR(last_login)) number_of_users,
                        WEEKOFYEAR(last_login) week_number,
                        WEEKOFYEAR(CURRENT_DATE()) current_week
                    FROM
                        user
                    JOIN player p on user.id = p.user_id
                    GROUP BY
                        WEEKOFYEAR(last_login)) w
                ORDER BY
                    w.week_number DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();
        return $results;
    }

    public function weeklyPoints()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT
                    SUM(CASE
                        WHEN l.previous_location_id IS NULL AND IFNULL(kept,0) = 0 THEN 1
                        WHEN l.previous_location_id IS NULL AND IFNULL(kept,0) = 1 THEN 1
                        WHEN l.previous_location_id IS NOT NULL AND IFNULL(kept,0) = 0 THEN 1
                        ELSE 0
                    END) points,
                   WEEKOFYEAR(l.found_on) week,
                    p.first_name,
                    p.surname
                FROM
                    location l
                JOIN player p on l.found_by_id = p.id
                GROUP BY
                     WEEKOFYEAR(l.found_on),
                    p.first_name,
                    p.surname
                ORDER BY
                    week DESC,
                    points DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();
        return $results;
    }
}
