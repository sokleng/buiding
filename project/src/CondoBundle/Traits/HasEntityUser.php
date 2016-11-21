<?php

namespace CondoBundle\Traits;

use WeBridge\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

trait HasEntityUser
{
    /**
     * The user being the resident of a specific unit over a time period.
     *
     * @var User
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *     targetEntity="WeBridge\UserBundle\Entity\User"
     * )
     */
    private $user;

    /**
     * Get base user.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Gets the user id.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->user->getId();
    }
}
