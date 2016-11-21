<?php

namespace DeveloperBundle\Traits;

use CondoBundle\Constant\SecurityRole;
use DeveloperBundle\Entity\Developer;
use CondoBundle\Traits\HasControllerUtils;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

trait HasDeveloperControllerUtils
{
    use HasControllerUtils;

    /**
     * Merges a given array of response parameters with the mandatory
     * parameters for the developer routes.
     *
     * @param array $params
     *
     * @return array
     */
    protected function getResponseParameters($params)
    {
        $developers = $this->getDeveloperRepository()
            ->findDevelopersByManager($this->getUser())
            ->getQuery()
            ->getResult()
        ;

        return array_merge(
            [
                'developers' => $developers,
            ],
            $params
        );
    }

    /**
     * Asserts that the current user can access the given developer through
     * the developer space.
     *
     * @param Developer $developer
     * @param null      $object
     */
    protected function assertCanAccessDeveloper(
        Developer $developer,
        $object = null
    ) {
        $user = $this->getUser();

        // User does not have the correct role,
        // should not happens as the firewall would watch for it.
        if (!$user->hasRole(SecurityRole::DEVELOPER)) {
            throw new AccessDeniedException(
                'User does not have rights to access developer.'
            );
        }

        // User does not belong to the given develoepr.
        if (!$developer->getManagers()->contains($user)) {
            throw new AccessDeniedException(
                'User is not a member of developer.'
            );
        }
    }
}
