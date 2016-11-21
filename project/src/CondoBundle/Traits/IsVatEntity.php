<?php

namespace CondoBundle\Traits;

use Doctrine\ORM\Mapping as ORM;

trait IsVatEntity
{
    /**
     * The IncomeAndExpend VAT.
     *
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $vat;

    /**
     * @return float
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @param float $vat
     *
     * @return $this
     */
    public function setVat($vat)
    {
        $this->vat = $vat;

        return $this;
    }
}
