<?php

namespace App\Helper;

use Doctrine\ORM\EntityManager;

/**
 * Class Orm
 * @package App\Helper
 */
class Orm implements OrmInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * Orm constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param $ql
     * @return \Doctrine\ORM\Query
     */
    public function createQuery($ql)
    {
        return $this->em->createQuery($ql);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createQueryBuilder()
    {
        return $this->em->createQueryBuilder();
    }

    /**
     * @param $entity
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     */
    public function getRepository($entity)
    {
        return $this->em->getRepository($entity);
    }

    /**
     * @param $object
     * @return $this
     * @throws \Doctrine\ORM\ORMException
     */
    public function persist($object)
    {
        $this->em->persist($object);
        return $this;
    }

    /**
     * @param $object
     * @return $this
     * @throws \Doctrine\ORM\ORMException
     */
    public function remove($object)
    {
        $this->em->remove($object);
        return $this;
    }

    /**
     * @return $this
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function flush()
    {
        $this->em->flush();
        return $this;
    }

    /**
     * @param $entity
     * @param array $criteria
     * @return mixed
     */
    public function find($entity, $criteria = array())
    {
        $findMethod = is_scalar($criteria) ? 'find' : 'findOneBy';
        $repository = $this->em->getRepository($entity);
        return $repository->$findMethod($criteria);
    }

    /**
     * @param $entity
     * @return array|object[]
     */
    public function findAll($entity)
    {
        $repository = $this->em->getRepository($entity);
        return $repository->findAll();
    }
}
