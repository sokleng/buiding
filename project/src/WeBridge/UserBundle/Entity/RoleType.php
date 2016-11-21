<?php

namespace WeBridge\UserBundle\Entity;

use CondoBundle\Traits\HasEntityId;
use CondoBundle\Traits\HasEntityName;
use Doctrine\ORM\Mapping as ORM;

/**
 * RoleType.
 *
 * @ORM\Table(name="role_type")
 * @ORM\Entity(repositoryClass="WeBridge\UserBundle\Repository\RoleTypeRepository")
 */
class RoleType
{
    use HasEntityId;
    use HasEntityName;
}
