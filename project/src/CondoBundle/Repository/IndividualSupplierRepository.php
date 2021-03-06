<?php

namespace CondoBundle\Repository;

use Doctrine\ORM\EntityRepository;
use CondoBundle\Entity\Condominium;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * IndividualSupplierRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class IndividualSupplierRepository extends EntityRepository
{
    /**
     * Gets all individual suppliers for given a condominium.
     *
     * @param Condominium $condominium
     *
     * @return QueryBuilder
     */
    public function findAllIndividualSuppliersForCondo(Condominium $condominium)
    {
        return $this->createQueryBuilder('individualSupplier')
            ->where('individualSupplier.condominium = :condominium')
            ->setParameter('condominium', $condominium);
    }

    /**
     * Gets all individual suppliers for given name and condominium.
     *
     * @param Condominium $condominium
     * @param $name
     *
     * @return QueryBuilder
     */
    public function findAllIndividualSuppliersForNameAndCondo(
        Condominium $condominium,
        $name
    ) {
        $result = $this
            ->createQueryBuilder('individualSupplier')
            ->where('individualSupplier.condominium = :condominium')
            ->setParameter('condominium', $condominium);
        if ($name !== '') {
            $result
                ->andWhere('individualSupplier.name LIKE :name')
                ->setParameter('name', $name.'%');
        }

        return $result;
    }
}
