<?php

namespace CondoBundle\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

trait HasEntityCreationDate
{
    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetimetz")
     */
    private $creationDate;

    /**
     * @return DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }
}
