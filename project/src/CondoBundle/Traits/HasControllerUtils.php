<?php

namespace CondoBundle\Traits;

use Doctrine\Common\Persistence\ObjectManager;
use WeBridge\UserBundle\Entity\User;

trait HasControllerUtils
{
    use HasRepositories;

    /**
     * @return ObjectManager
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * Persists and flush an entity.
     *
     * @param mixed $entity
     */
    protected function persistAndFlush($entity)
    {
        $this->getEntityManager()
            ->persist($entity);
        $this->getEntityManager()
            ->flush();
    }

    /**
     * @param mixed $entity
     */
    protected function removeAndFlush($entity)
    {
        $this->getEntityManager()
            ->remove($entity);
        $this->getEntityManager()
            ->flush();
    }

    /**
     * Gets the current user.
     *
     * @return User
     */
    abstract public function getUser();
}
