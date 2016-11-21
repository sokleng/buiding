<?php

namespace WeBridge\UserBundle\Entity;

use CondoBundle\Traits\HasEntityId;
use CondoBundle\Traits\HasEntityName;
use Doctrine\ORM\Mapping as ORM;

/**
 * UserModule.
 *
 * @ORM\Table(name="user_module")
 * @ORM\Entity(repositoryClass="WeBridge\UserBundle\Repository\UserModuleRepository")
 */
class UserModule
{
    use HasEntityId;
    use HasEntityName;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $key;

    /**
     * @var RoleType
     *
     * @ORM\JoinColumn(nullable=true)
     * @ORM\ManyToOne(targetEntity="WeBridge\UserBundle\Entity\RoleType")
     */
    private $roleType;

    /**
     * @return RoleType
     */
    public function getRoleType()
    {
        return $this->roleType;
    }

    /**
     * @param RoleType $roleType
     *
     * @return UserModule
     */
    public function setRoleType(RoleType $roleType)
    {
        $this->roleType = $roleType;

        return $this;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     *
     * @return UserModule
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }
}
