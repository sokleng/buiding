<?php

namespace ProjectRealtyBundle\Traits;

use CondoBundle\Constant\SecurityRole;
use CondoBundle\Traits\HasControllerUtils;
use ProjectRealtyBundle\Entity\CondominiumProject;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

trait HasProjectControllerUtils
{
    use HasControllerUtils;

    /**
     * Merges a given array of response parameters with the mandatory
     * parameters for the project space routes.
     *
     * @param array $params
     *
     * @return array
     */
    protected function getResponseParameters($params)
    {
        if ($this->getUser()->hasRole(SecurityRole::ADMIN)) {
            $projects = $this->getCondominiumProjectRepository()->findAll();
        } else {
            $projects = $this->getCondominiumProjectRepository()
                ->findManagerProjects($this->getUser())
                ->getQuery()
                ->getResult()
            ;
        }

        return array_merge(
            [
                'projects' => $projects,
            ],
            $params
        );
    }

    /**
     * Asserts that the current user can access the given project through
     * the project management space.
     *
     * @param CondominiumProject $project
     * @param mixed              $object
     */
    protected function assertCanAccessProject(
        CondominiumProject $project,
        $object = null
    ) {
        $user = $this->getUser();

        // User does not have the correct role,
        // should not happens as the firewall would watch for it.
        if (!$user->hasRole(SecurityRole::PROJECT) || !$user->hasRole(SecurityRole::ADMIN)) {
            throw new AccessDeniedException(
                'User does not have rights to access Condominium Project.'
            );
        }

        // User does not belong to the given condo.
        if (!$project->getManagers()->contains($user)) {
            throw new AccessDeniedException(
                'User is not a member of project.'
            );
        }

        // Given object does not belong to the given condo project.
        if (
            !empty($object) &&
            method_exists($object, 'getProject') &&
            $object->getProject() != $project
        ) {
            throw new AccessDeniedException(
                'Given object does not belong to the given Condominium Project.'
            );
        }
    }
}
