<?php

namespace CondoBundle\Traits;

trait HasEntityGrandTotal
{
    /**
     * The grand total.
     *
     * @var float
     *
     * @ORM\Column(type="float", nullable=false)
     */
    private $grandTotal;

    /**
     * @return float
     */
    public function getGrandTotal()
    {
        return $this->grandTotal;
    }

    /**
     * @param float $grandTotal
     *
     * @return $this
     */
    public function setGrandTotal($grandTotal)
    {
        $this->grandTotal = $grandTotal;

        return $this;
    }
}
