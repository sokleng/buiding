<?php

namespace CondoBundle\Traits;

use Doctrine\ORM\Mapping as ORM;
use CondoBundle\Entity\Condominium;

trait HasEntityCondominium
{
    /**
     * @var Condominium
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\Condominium"
     * )
     */
    private $condominium;

    // region Accessors

    /**
     * @return Condominium
     */
    public function getCondominium()
    {
        return $this->condominium;
    }

    /**
     * @param Condominium $condominium
     *
     * @return ProfitCategory
     */
    public function setCondominium(Condominium $condominium)
    {
        $this->condominium = $condominium;

        return $this;
    }
}
