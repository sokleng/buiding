<?php

namespace CondoBundle\Traits;

use Doctrine\ORM\Mapping as ORM;
use CondoBundle\Entity\ExchangeSetting;

trait HasEntityExchangeSetting
{
    /**
     * @var ExchangeSetting
     *
     * @ORM\JoinColumn(nullable=true)
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\ExchangeSetting"
     * )
     */
    private $exchangeSetting;

    // region Accessors

    /**
     * @return ExchangeSetting
     */
    public function getExchangeSetting()
    {
        return $this->exchangeSetting;
    }

    /**
     * @param ExchangeSetting $exchangeSetting
     *
     * @return $this
     */
    public function setExchangeSetting(ExchangeSetting $exchangeSetting)
    {
        $this->exchangeSetting = $exchangeSetting;

        return $this;
    }
}
