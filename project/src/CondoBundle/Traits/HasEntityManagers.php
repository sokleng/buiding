<?php

namespace CondoBundle\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use WeBridge\UserBundle\Entity\User;

trait HasEntityManagers
{
    /**
     * @var User[]|ArrayCollection
     *
     * @ORM\ManyToMany(
     *     targetEntity="WeBridge\UserBundle\Entity\User"
     * )
     */
    private $managers;

    /**
     * Adds a user as a manager if he isn't already a manager.
     *
     * @param User $user
     *
     * @return $this
     */
    public function addManager(User $user)
    {
        if (!$this->managers->contains($user)) {
            $this->managers->add($user);
        }

        return $this;
    }

    /**
     * Removes a manager if it exists.
     *
     * @param User $user
     *
     * @return $this
     */
    public function removeManager(User $user)
    {
        $this->managers->removeElement($user);

        return $this;
    }

    /**
     * @return ArrayCollection|User[]
     */
    public function getManagers()
    {
        return $this->managers;
    }
}
