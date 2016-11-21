<?php

namespace RealtyCompanyBundle\Traits;

use CondoBundle\Constant\SecurityRole;
use CondoBundle\Traits\HasControllerUtils;
use RealtyCompanyBundle\Entity\RealtyCompany;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

trait HasRealtyControllerUtils
{
    use HasControllerUtils;

    /**
     * Merges a given array of response parameters with the mandatory
     * parameters for the RealtyCompany space routes.
     *
     * @param array $params
     *
     * @return array
     */
    protected function getResponseParameters($params)
    {
        $user = $this->getUser();
        $realtyCompanies = $this->getRealtyCompanyRepository()
            ->findAllRealtyCompanyByManager($user);

        return array_merge(
            [
                'realtyCompanies' => $realtyCompanies,
            ],
            $params
        );
    }

    /**
     * Asserts that the current user can access the given realty company through
     * the realty company management space.
     *
     * @param RealtyCompany $company
     * @param mixed         $object
     */
    protected function assertCanAccessRealtyCompany(
        RealtyCompany $company,
        $object = null
    ) {
        $user = $this->getUser();

        // User does not have the correct role,
        // should not happens as the firewall would watch for it.
        if (!$user->hasRole(SecurityRole::REALTY_COMPANY)) {
            throw new AccessDeniedException(
                'User does not have rights to access Realty Company.'
            );
        }

        // User does not belong to the given condo.
        if (!$company->getManagers()->contains($user)) {
            throw new AccessDeniedException(
                'User is not a member of Company.'
            );
        }

        // Given object does not belong to the given realty company.
        if (
            !empty($object) &&
            method_exists($object, 'getRealtyCompany') &&
            $object->getRealtyCompany() != $company
        ) {
            throw new AccessDeniedException(
                'Given object does not belong to the given Realty Company.'
            );
        }
    }
}
