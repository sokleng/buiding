<?php

namespace CondoBundle\Traits;

use Symfony\Component\Validator\Constraints as Assert;

trait HasEntityPrice
{
    /**
     * Price of the item in USD.
     *
     * @var float
     *
     * @ORM\Column(type="float", nullable=false)
     * @Assert\Range(min="0")
     */
    private $price;

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     *
     * @return ShopItem
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }
}
