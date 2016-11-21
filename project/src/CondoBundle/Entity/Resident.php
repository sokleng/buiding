<?php

namespace CondoBundle\Entity;

use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityId;
use CondoBundle\Traits\HasEntityUser;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a residency from one user to one unit over a specific time range.
 *
 * @ORM\Table(name="resident")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\ResidentRepository")
 */
class Resident
{
    use HasEntityId;
    use HasEntityCreationDate;
    use HasEntityUser;

    public function __construct()
    {
        $this->creationDate = new DateTime();
    }

    /**
     * The Unit occupied by this resident.
     *
     * @var Unit
     *
     * @ORM\JoinColumn(nullable=false, onDelete="SET NULL")
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\Unit",
     *     inversedBy="residents",
     *     fetch="EAGER"
     * )
     */
    private $unit;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $residencyStart;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $residencyEnd;

    //region Accessors
    /**
     * @return Unit
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param Unit $unit
     *
     * @return Resident
     */
    public function setUnit(Unit $unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getResidencyStart()
    {
        return $this->residencyStart;
    }

    /**
     * @param DateTime $residencyStart
     */
    public function setResidencyStart(DateTime $residencyStart)
    {
        $this->residencyStart = $residencyStart;
    }

    /**
     * @return DateTime
     */
    public function getResidencyEnd()
    {
        return $this->residencyEnd;
    }

    /**
     * @param DateTime $residencyEnd
     */
    public function setResidencyEnd(DateTime $residencyEnd)
    {
        $this->residencyEnd = $residencyEnd;
    }
    //endregion
}
