<?php

namespace CondoBundle\Entity;

use CondoBundle\Traits\HasEntityAddress;
use CondoBundle\Traits\HasEntityVat;
use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityId;
use CondoBundle\Traits\HasEntityManagers;
use CondoBundle\Traits\HasEntityName;
use CondoBundle\Traits\HasEntityCurrency;
use CondoBundle\Traits\HasEntityExchangeSetting;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Condominium.
 *
 * @ORM\Table(name="condominium")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\CondominiumRepository")
 */
class Condominium
{
    use HasEntityId;
    use HasEntityCreationDate;
    use HasEntityName;
    use HasEntityAddress;
    use HasEntityManagers;
    use HasEntityVat;
    use HasEntityCurrency;
    use HasEntityExchangeSetting;

    public function __construct()
    {
        $this->creationDate = new DateTime();
        $this->units = new ArrayCollection();
        $this->managers = new ArrayCollection();
    }

    /**
     * The district of the condominium, used to help decide services coverage.
     *
     * @var District
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\District",
     *     fetch="EAGER"
     * )
     */
    private $district;

    /**
     * @var Unit[]
     *
     * @ORM\OneToMany(
     *     targetEntity="CondoBundle\Entity\Unit",
     *     mappedBy="condominium"
     * )
     */
    private $units;

    //region Accessors

    /**
     * @return District
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * @param District $district
     *
     * @return Condominium
     */
    public function setDistrict(District $district)
    {
        $this->district = $district;

        return $this;
    }

    /**
     * @return Unit[]|ArrayCollection
     */
    public function getUnits()
    {
        return $this->units;
    }

    //endregion
}
