<?php

namespace CondoBundle\Traits;

use Doctrine\ORM\Mapping as ORM;

trait HasEntityVat
{
    /**
     * @var bool
     * @ORM\Column(type="boolean",  options={"default"=true})
     */
    private $isVat;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rate;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $vatTin;

    /**
     * @return bool
     */
    public function isVat()
    {
        return $this->isVat;
    }

    /**
     * @param bool $isVat
     *
     * @return IsVat
     */
    public function setIsVat($isVat)
    {
        $this->isVat = $isVat;

        return $this;
    }

    /**
     * @return int
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param int $rate
     *
     * @return Rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @return string
     */
    public function getVatTin()
    {
        return $this->vatTin;
    }

    /**
     * @param string $vatTin
     *
     * @return VatTin
     */
    public function setVatTin($vatTin)
    {
        $this->vatTin = $vatTin;

        return $this;
    }
}
