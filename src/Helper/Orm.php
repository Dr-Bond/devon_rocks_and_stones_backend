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
        try {
            $this->em->persist($object);
            return $this;
        } catch (\Exception $e) {
            return 'Error: '.$e->getMessage();
        }

    }

    public function remove($object)
    {
        try {
            $this->em->remove($object);
            return $this;
        } catch (\Exception $e) {
            return 'Error: '.$e->getMessage();
        }
    }

    public function flush($entity = null)
    {
        try {
            $this->em->flush($entity);
            return $this;
        } catch (\Exception $e) {
            return 'Error: '.$e->getMessage();
        }
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
