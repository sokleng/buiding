<?php

namespace CondoBundle\Traits;

use CondoBundle\Entity\Currency;

trait HasEntityCurrency
{
    /**
     * @var Currency
     *
     * @ORM\JoinColumn(nullable=true)
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\Currency",
     *     fetch="EAGER"
     * )
     */
    private $currency;

    /**
     * @param Currency $currency
     *
     * @return $this
     */
    public function setCurrency(Currency $currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}
