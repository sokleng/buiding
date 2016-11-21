<?php

namespace WeBridge\UserBundle\Entity;

use CondoBundle\Traits\HasEntityName;
use CondoBundle\Traits\HasEntityPhone;
use CondoBundle\Traits\HasEntityPicture;
use Doctrine\ORM\Mapping\Column;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represent a base user for login purposes only. Other classes (non-inherited)
 * with the same id are here to describe the different user types possible.
 * This is useful to allow multiple user type simultaneously.
 *
 *
 * @ORM\Entity(repositoryClass="UserRepository")
 * @ORM\Table(name="fos_user")
 *
 * @ORM\AttributeOverrides({
 *     @ORM\AttributeOverride(name="email", column=@Column(type="string", nullable=true)),
 *     @ORM\AttributeOverride(name="enabled", column=@Column(type="boolean", nullable=true)),
 *     @ORM\AttributeOverride(name="salt", column=@Column(nullable=true)),
 *     @ORM\AttributeOverride(name="locked", column=@Column(type="boolean", nullable=true)),
 *     @ORM\AttributeOverride(name="expired", column=@Column(type="boolean", nullable=true)),
 *     @ORM\AttributeOverride(name="emailCanonical", column=@Column(nullable=true)),
 *     @ORM\AttributeOverride(name="credentialsExpired", column=@Column(type="boolean", nullable=true)),
 *     @ORM\AttributeOverride(name="roles", column=@Column(type="array", nullable=true)),
 * })
 */
class User extends BaseUser
{
    use HasEntityName;
    use HasEntityPhone;
    use HasEntityPicture;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The roleType owning that user.
     *
     * @var RoleType
     *
     * @ORM\JoinColumn(nullable=true)
     * @ORM\ManyToOne(
     *      targetEntity="WeBridge\UserBundle\Entity\RoleType"
     * )
     */
    private $space;

    /**
     * The space id store for condo or service id.
     *
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $spaceType;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return RoleType
     */
    public function getSpace()
    {
        return $this->space;
    }

    /**
     * @param RoleType $space
     *
     * @return User
     */
    public function setSpace(RoleType $space)
    {
        $this->space = $space;

        return $this;
    }

    /**
     * @return int
     */
    public function getSpaceType()
    {
        return $this->spaceType;
    }

    /**
     * @param int $spaceType
     *
     * @return User
     */
    public function setSpaceType($spaceType)
    {
        $this->spaceType = $spaceType;

        return $this;
    }
}
