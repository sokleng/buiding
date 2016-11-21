<?php

namespace CondoBundle\Traits;

use Doctrine\ORM\Mapping as ORM;

trait HasEntityContactNumber
{
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $contactNumber;

    /**
     * @return string
     */
    public function getContactNumber()
    {
        return $this->contactNumber;
    }

    /**
     * @param string $contactNumber
     *
     * @return $this
     */
    public function setContactNumber($contactNumber)
    {
        $this->contactNumber = $contactNumber;

        return $this;
    }
}
