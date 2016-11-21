<?php

namespace CondoBundle\Traits;

use Doctrine\ORM\Mapping as ORM;

trait HasEntityAddress
{
    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    private $address;

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     *
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }
}
