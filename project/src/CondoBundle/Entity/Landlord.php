<?php

namespace CondoBundle\Entity;

use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityId;
use CondoBundle\Traits\HasEntityUser;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Landlord.
 *
 * @ORM\Table(name="landlord")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\LandlordRepository")
 */
class Landlord
{
    use HasEntityId;
    use HasEntityUser;
    use HasEntityCreationDate;

    public function __construct()
    {
        $this->creationDate = new DateTime();
    }
}
