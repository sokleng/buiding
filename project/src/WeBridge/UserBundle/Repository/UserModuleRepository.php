<?php

namespace WeBridge\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use WeBridge\UserBundle\Entity\RoleType;

/**
 * UserModuleRepository.
 */
class UserModuleRepository extends EntityRepository
{
    /**
     * Get Modules by roleType.
     *
     * @param RoleType $roleType
     *
     * @return array
     */
    public function findAllModulesByRoleType(RoleType $roleType)
    {
        return $this->createQueryBuilder('userModule')
            ->where('userModule.roleType = :roleType')
            ->setParameter('roleType', $roleType)
            ->orderBy('userModule.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get roleType by role.
     *
     * @param array $roleType
     *
     * @return array
     */
    public function findUserRoleTypeBy($roles)
    {
        return $this->createQueryBuilder('userModule')
            ->where('userModule.key IN (:keys)')
            ->setParameter('keys', $roles)
            ->getQuery()
            ->getResult();
    }
}
