<?php

namespace App\Helper;

use Doctrine\ORM\EntityManager;

class Orm implements OrmInterface
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function createQuery($ql)
    {
        return $this->em->createQuery($ql);
    }

    public function createQueryBuilder()
    {
        return $this->em->createQueryBuilder();
    }

    public function getRepository($entity)
    {
        return $this->em->getRepository($entity);
    }

    public function persist($object)
    {
        $this->em->persist($object);
        return $this;
    }

    public function remove($object)
    {
        $this->em->remove($object);
        return $this;
    }

    public function flush($entity = null)
    {
        $this->em->flush($entity);
        return $this;
    }

    public function find($entity, $criteria = array())
    {
        $findMethod = is_scalar($criteria) ? 'find' : 'findOneBy';
        $repository = $this->em->getRepository($entity);
        return $repository->$findMethod($criteria);
    }

    public function findAll($entity)
    {
        $repository = $this->em->getRepository($entity);
        return $repository->findAll();
    }
}
