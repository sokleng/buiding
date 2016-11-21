<?php

namespace WeBridge\UserBundle\Entity;

use CondoBundle\Constant\SecurityRole;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use CondoBundle\Entity\Condominium;

/**
 * UserRepository.
 */
class UserRepository extends EntityRepository
{
    public function updateServiceRoleForUser(
        User $user,
        TokenStorage $storage
    ) {
        $membershipCount = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('count(managers)')
            ->from('CondoBundle:ServiceProvider', 'provider')
            ->join('provider.managers', 'managers')
            ->where('managers = :manager')
            ->setParameter('manager', $user)
            ->getQuery()
            ->getSingleScalarResult();

        if ($membershipCount > 0) {
            $user->addRole(SecurityRole::SERVICE);
        } else {
            $user->removeRole(SecurityRole::SERVICE);
        }

        // Resetting the token
        $newToken = new UsernamePasswordToken(
            $user,
            null,
            'main',
            $user->getRoles()
        );
        $storage->setToken($newToken);

        $this->getEntityManager()
            ->flush($user);
    }

    public function updateCondominiumRoleForUser(
        User $user,
        TokenStorage $storage
    ) {
        $membershipCount = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('count(managers)')
            ->from('CondoBundle:Condominium', 'condominium')
            ->join('condominium.managers', 'managers')
            ->where('managers = :manager')
            ->setParameter('manager', $user)
            ->getQuery()
            ->getSingleScalarResult();

        if ($membershipCount > 0) {
            $user->addRole(SecurityRole::CONDOMINIUM);
        } else {
            $user->removeRole(SecurityRole::CONDOMINIUM);
        }

        // Resetting the token
        $newToken = new UsernamePasswordToken(
            $user,
            null,
            'main',
            $user->getRoles()
        );
        $storage->setToken($newToken);

        $this->getEntityManager()
            ->flush($user);
    }

    public function updateProjectRoleForUser(
        User $user,
        TokenStorage $storage
    ) {
        $membershipCount = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('count(managers)')
            ->from('ProjectRealtyBundle:CondominiumProject', 'project')
            ->join('project.managers', 'managers')
            ->where('managers = :manager')
            ->setParameter('manager', $user)
            ->getQuery()
            ->getSingleScalarResult();

        if ($membershipCount > 0) {
            $user->addRole(SecurityRole::PROJECT);
        } else {
            $user->removeRole(SecurityRole::PROJECT);
        }

        // Resetting the token
        $newToken = new UsernamePasswordToken(
            $user,
            null,
            'main',
            $user->getRoles()
        );
        $storage->setToken($newToken);

        $this->getEntityManager()
            ->flush($user);
    }

    public function updateRealtyCompanyRoleForUser(
        User $user,
        TokenStorage $storage
    ) {
        $membershipCount = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('count(managers)')
            ->from('RealtyCompanyBundle:RealtyCompany', 'realtyCompany')
            ->join('realtyCompany.managers', 'managers')
            ->where('managers = :manager')
            ->setParameter('manager', $user)
            ->getQuery()
            ->getSingleScalarResult();

        if ($membershipCount > 0) {
            $user->addRole(SecurityRole::REALTY_COMPANY);
        } else {
            $user->removeRole(SecurityRole::REALTY_COMPANY);
        }
        // Resetting the token
        $newToken = new UsernamePasswordToken(
            $user,
            null,
            'main',
            $user->getRoles()
        );
        $storage->setToken($newToken);

        $this->getEntityManager()
            ->flush($user);
    }

    public function updateDeveloperRoleForUser(
        User $user,
        TokenStorage $storage
    ) {
        $membershipCount = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('count(managers)')
            ->from('DeveloperBundle:Developer', 'developer')
            ->join('developer.managers', 'managers')
            ->where('managers = :manager')
            ->setParameter('manager', $user)
            ->getQuery()
            ->getSingleScalarResult();

        if ($membershipCount > 0) {
            $user->addRole(SecurityRole::DEVELOPER);
        } else {
            $user->removeRole(SecurityRole::DEVELOPER);
        }

        // Resetting the token
        $newToken = new UsernamePasswordToken(
            $user,
            null,
            'main',
            $user->getRoles()
        );
        $storage->setToken($newToken);

        $this->getEntityManager()
            ->flush($user);
    }

    /**
     * Finds a single user by either phone or email.
     *
     * @param $text
     *
     * @return null|void|User
     */
    public function findOneUserByPhoneOrEmail($text)
    {
        $result = $this->createQueryBuilder('u')
            ->where('u.email = :text')
            ->orWhere('u.phoneNumber = :text')
            ->setParameter('text', $text)
            ->getQuery()
            ->getResult()
        ;

        if (empty($result)) {
            return;
        }

        return $result[0];
    }

    /**
     * Get user by email.
     *
     * @param $email
     *
     * @return null|void|User
     */
    public function findUserByEmail($email)
    {
        $result = $this->createQueryBuilder('user')
            ->where('user.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getResult()
        ;

        if (empty($result)) {
            return;
        }

        return $result[0];
    }
}
