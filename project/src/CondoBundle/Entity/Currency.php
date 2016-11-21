<?php

namespace CondoBundle\Entity;

use CondoBundle\Traits\HasEntityId;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a currency for condominium or service that use.
 *
 * @ORM\Table(name="currency")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\CurrencyRepository")
 */
class Currency
{
    use HasEntityId;

    /**
     * The currency is name of currency like USD, Riel, Yuan, ...
     *
     * @var currency
     * @ORM\Column(type="string")
     */
    private $currency;

    /**
     *   The sign is sign of currency like $.
     *
     * @ORM\Column(type="string")
     */
    private $sign;

    /**
     * @param $currency
     *
     * @return Currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param $sign
     *
     * @return Currency
     */
    public function setSign($sign)
    {
        $this->sign = $sign;

        return $this;
    }

    /**
     * @return sign
     */
    public function getSign()
    {
        return $this->sign;
    }
}
