<?php

namespace CondoBundle\Traits;

trait HasEntitySubTotal
{
    /**
     * The sub total.
     *
     * @var float
     *
     * @ORM\Column(type="float", nullable=false)
     */
    private $subTotal;

    /**
     * @return float
     */
    public function getSubTotal()
    {
        return $this->subTotal;
    }

    /**
     * @param float $subTotal
     *
     * @return $this
     */
    public function setSubTotal($subTotal)
    {
        $this->subTotal = $subTotal;

        return $this;
    }
}
