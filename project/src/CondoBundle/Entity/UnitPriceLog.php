<?php

namespace CondoBundle\Entity;

use CondoBundle\Traits\HasEntityId;
use DateTime;
use WeBridge\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * UnitPriceLog.
 *
 * @ORM\Table(name="unit_price_log")
 * @ORM\Entity()
 */
class UnitPriceLog
{
    use HasEntityId;

    public function __construct()
    {
        $this->modifyDate = new DateTime();
    }

    /**
     * @var Unit
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\Unit",
     *     inversedBy="units"
     * )
     */
    private $unit;

    /**
     * The Unit Old Price.
     *
     * @var float
     *
     * @ORM\Column(type="float", nullable=false)
     */
    private $oldPrice;

    /**
     * The Unit New Price.
     *
     * @var float
     *
     * @ORM\Column(type="float", nullable=false)
     */
    private $newPrice;

    /**
     * The Unit Editor.
     *
     * @var user
     *
     * @ORM\JoinColumn(nullable=true)
     * @ORM\ManyToOne(
     *     targetEntity="WeBridge\UserBundle\Entity\User"
     * )
     */
    private $editBy;

    /**
     * The Unit modify date.
     *
     * @var DateTime
     *
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $modifyDate;

    /**
     * @return float
     */
    public function getOldPrice()
    {
        return $this->oldPrice;
    }

    /**
     * @param float $oldPrice
     *
     * @return UnitPriceLog
     */
    public function setOldPrice($oldPrice)
    {
        $this->oldPrice = $oldPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getNewPrice()
    {
        return $this->newPrice;
    }

    /**
     * @param float $newPrice
     *
     * @return UnitPriceLog
     */
    public function setNewPrice($newPrice)
    {
        $this->newPrice = $newPrice;

        return $this;
    }

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
     * @return UnitPriceLog
     */
    public function setUnit(Unit $unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return User $createBy
     */
    public function getEditBy()
    {
        return $this->editBy;
    }

    /**
     * @param User $editBy
     *
     * @return UnitPriceLog
     */
    public function setEditBy(User $editBy)
    {
        $this->editBy = $editBy;

        return $this;
    }

    /**
     * @return DateTime $modifyDate
     */
    public function getModifyDate()
    {
        return $this->modifyDate;
    }

    /**
     * @param DateTime $modifyDate
     *
     * @return UnitPriceLog
     */
    public function setModifyDate(DateTime $modifyDate)
    {
        $this->modifyDate = $modifyDate;

        return $this;
    }
}
