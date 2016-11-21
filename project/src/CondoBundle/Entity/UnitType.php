<?php

namespace CondoBundle\Entity;

use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityId;
use CondoBundle\Traits\HasEntityUnitTypeFields;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * The unit type represents a type of unit within a specific Condominium.
 * Unit types as it is are not meant to be shared between Condos.
 *
 * @ORM\Table(name="unit_type")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\UnitTypeRepository")
 */
class UnitType
{
    use HasEntityId;
    use HasEntityCreationDate;
    use HasEntityUnitTypeFields;

    public function __construct()
    {
        $this->creationDate = new DateTime();
    }

    /**
     * The condominium owning that unit type.
     *
     * @var Condominium
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(targetEntity="CondoBundle\Entity\Condominium")
     */
    private $condominium;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $commonAreaSize;

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
     * @return UnitType
     */
    public function setCondominium(Condominium $condominium)
    {
        $this->condominium = $condominium;

        return $this;
    }

    /**
     * Gets the total area size including common area if set.
     *
     * @return float
     */
    public function getTotalSize()
    {
        return $this->size + (empty($this->commonAreaSize) ? 0 : $this->commonAreaSize);
    }

    /**
     * @return float
     */
    public function getCommonAreaSize()
    {
        return $this->commonAreaSize;
    }

    /**
     * @param float $commonAreaSize
     *
     * @return UnitType
     */
    public function setCommonAreaSize($commonAreaSize)
    {
        $this->commonAreaSize = $commonAreaSize;

        return $this;
    }
}
