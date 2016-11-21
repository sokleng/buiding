<?php

namespace CondominiumManagementBundle\Traits;

use CondoBundle\Constant\SecurityRole;
use CondoBundle\Entity\Condominium;
use CondoBundle\Traits\HasControllerUtils;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

trait HasCondominiumManagementUtils
{
    use HasControllerUtils;

    /**
     * Merges a given array of response parameters with the mandatory
     * parameters for the Condominium Management routes.
     *
     * @param array $params
     *
     * @return array
     */
    protected function getResponseParameters($params)
    {
        $condominiums = $this->getCondominiumRepository()
            ->findManagerCondominiums($this->getUser())
            ->getQuery()
            ->getResult()
        ;

        return array_merge(
            [
                'condominiums' => $condominiums,
            ],
            $params
        );
    }

    /**
     * Asserts that the current user can access the given condominium through
     * the condominium management space.
     *
     * @param Condominium $condo
     * @param null        $object
     */
    protected function assertCanAccessCondominium(
        Condominium $condo,
        $object = null
    ) {
        $user = $this->getUser();

        // User does not have the correct role,
        // should not happens as the firewall would watch for it.
        if (!$user->hasRole(SecurityRole::CONDOMINIUM)) {
            throw new AccessDeniedException(
                'User does not have rights to access condominium.'
            );
        }

        // User does not belong to the given condo.
        if (!$condo->getManagers()->contains($user)) {
            throw new AccessDeniedException(
                'User is not a member of condominium.'
            );
        }

        // Given object does not belong to the given condo.
        if (
            !empty($object) &&
            method_exists($object, 'getCondominium') &&
            $object->getCondominium() != $condo
        ) {
            throw new BadRequestHttpException(
                'Given object does not belong to the given Condominium.'
            );
        }
    }
}
