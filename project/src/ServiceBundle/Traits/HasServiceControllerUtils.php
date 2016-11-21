<?php

namespace ServiceBundle\Traits;

use CondoBundle\Constant\SecurityRole;
use CondoBundle\Entity\Service;
use CondoBundle\Traits\HasControllerUtils;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

trait HasServiceControllerUtils
{
    use HasControllerUtils;

    /**
     * Gets the services available for the current service manager.
     *
     * @return Service[]|ArrayCollection
     */
    protected function getManagerServices()
    {
        return $this->getServiceRepository()
            ->findServicesForManager($this->getUser())
            ->getQuery()
            ->getResult();
    }

    /**
     * Asserts user has access to a given service management page.
     * This is not related to client access and should only be use
     * for the `/service` space.
     *
     * @param Service $service
     * @param mixed   $object
     */
    protected function assertUserCanAccessService(
        Service $service,
        $object = null
    ) {
        $user = $this->getUser();

        // User does not have the correct role,
        // should not happens as the firewall would watch for it.
        if (!$user->hasRole(SecurityRole::SERVICE)) {
            throw new AccessDeniedException(
                'User does not have rights to access service.'
            );
        }

        // User does not belong to the given service.
        if (!$service->getManagers()->contains($user)) {
            throw new AccessDeniedException(
                'User is not a member of service.'
            );
        }

        // Given object does not belong to the given service.
        if (
            !empty($object) &&
            method_exists($object, 'getService') &&
            $object->getService() != $service
        ) {
            throw new BadRequestHttpException(
                'Given object does not belong to the given service.'
            );
        }
    }
}
