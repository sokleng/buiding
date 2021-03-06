<?php

namespace ProjectRealtyBundle\Repository;

use Doctrine\ORM\EntityRepository;
use ProjectRealtyBundle\Entity\CondominiumProject;

/**
 * ProjectUnitRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProjectUnitRepository extends EntityRepository
{
    public function findByProject(CondominiumProject $project)
    {
        return $this->createQueryBuilder('unit')
            ->where('unit.project = :project')
            ->setParameter('project', $project)
            ->orderBy('unit.floor')
            ->addOrderBy('unit.roomNumber')
            ->addOrderBy('unit.type')
            ->addOrderBy('unit.price')
            ->addOrderBy('unit.status')
        ;
    }

    /**
     * @param array $filter
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function baseCountFilter(array $filter = [])
    {
        $qb = $this->createQueryBuilder('unit')
            ->select('COUNT(unit.id)')
        ;

        if (isset($filter['project'])) {
            $qb->andWhere('unit.project = :project')
                ->setParameter('project', $filter['project'])
            ;
        }

        if (isset($filter['unitType'])) {
            $qb->andWhere('unit.type = :unitType')
                ->setParameter('unitType', $filter['unitType'])
            ;
        }

        if (isset($filter['floor'])) {
            $qb->andWhere('unit.floor = :floor')
                ->setParameter('floor', $filter['floor'])
            ;
        }

        $qb->distinct();

        return $qb;
    }

    /**
     * Gets a count of unit matching a given filter.
     *
     * @param array $filter
     *
     * @return int
     */
    public function getCountByFilter(array $filter = [])
    {
        return $this->baseCountFilter($filter)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Gets a count of sold unit matching a given filter.
     * NOTE: So far counts a unit as sold if it has a payment, this might need to be
     * updated to match reality.
     *
     * @param array $filter
     *
     * @return int
     */
    public function getPaidCountByFilter(array $filter = [])
    {
        $qb = $this->baseCountFilter($filter)
            ->join('unit.payments', 'payments')
        ;

        if (isset($filter['from'])) {
            $qb->andWhere('payments.creationDate >= :from')
                ->setParameter('from', $filter['from'])
            ;
        }

        if (isset($filter['to'])) {
            $qb->andWhere('payments.creationDate <= :to')
                ->setParameter('to', $filter['to'])
            ;
        }

        return $qb->getQuery()->getSingleScalarResult();
    }
}
