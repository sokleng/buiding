<?php

namespace CondoBundle\Entity;

use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityId;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Condominium Unit.
 *
 * @ORM\Table(name="unit")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\UnitRepository")
 */
class Unit
{
    use HasEntityId;
    use HasEntityCreationDate;
    public function __construct()
    {
        $this->creationDate = new DateTime();
        $this->residents = new ArrayCollection();
        $this->clientUnits = new ArrayCollection();
        $this->isLocked = false;
    }

    /**
     * @var Condominium
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\Condominium",
     *     inversedBy="units"
     * )
     */
    private $condominium;

    /**
     * The floor number of that unit.
     *
     * @var int
     * @ORM\Column(type="smallint")
     */
    private $floor;

    /**
     * The room number associated with that unit.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=10)
     */
    private $roomNumber;

    /**
     * The optional unit type to help improve service.
     *
     * @var UnitType
     *
     * @ORM\JoinColumn(nullable=true)
     * @ORM\ManyToOne(targetEntity="CondoBundle\Entity\UnitType")
     */
    private $type;

    /**
     * The unit landlord.
     *
     * @var Landlord
     *
     * @ORM\JoinColumn(nullable=true)
     * @ORM\ManyToOne(targetEntity="CondoBundle\Entity\Landlord")
     */
    private $landlord;

    /**
     * The Unit Price.
     *
     * @var float
     *
     * @ORM\Column(type="float", nullable=false)
     */
    private $price;

    /**
     * The unit payBy.
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $payBy;

    /**
     * @var bool
     *
     * @ORM\Column(
     *     type="boolean",
     *     options={"default"=false}
     * )
     */
    private $isLocked;

    /**
     * @var Resident[]
     *
     * @ORM\OneToMany(
     *     targetEntity="CondoBundle\Entity\Resident",
     *     mappedBy="unit"
     * )
     */
    private $residents;

    /**
     * @var ClientUnit[]
     *
     * @ORM\OneToMany(
     *     targetEntity="CondoBundle\Entity\ClientUnit",
     *     mappedBy="unit"
     * )
     */
    private $clientUnits;

    /**
     * @return bool
     */
    public function isLocked()
    {
        return $this->isLocked;
    }

    /**
     * @param bool $isLocked
     *
     * @return Unit
     */
    public function setIsLocked($isLocked)
    {
        $this->isLocked = $isLocked;

        return $this;
    }

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
     * @return Unit
     */
    public function setCondominium(Condominium $condominium)
    {
        $this->condominium = $condominium;

        return $this;
    }

    /**
     * @return int
     */
    public function getFloor()
    {
        return $this->floor;
    }

    /**
     * @param int $floor
     *
     * @return Unit
     */
    public function setFloor($floor)
    {
        $this->floor = $floor;

        return $this;
    }

    /**
     * @return string
     */
    public function getRoomNumber()
    {
        return $this->roomNumber;
    }

    /**
     * @param string $roomNumber
     *
     * @return Unit
     */
    public function setRoomNumber($roomNumber)
    {
        $this->roomNumber = $roomNumber;

        return $this;
    }

    /**
     * @return UnitType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param UnitType $type
     *
     * @return Unit
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Landlord
     */
    public function getLandlord()
    {
        return $this->landlord;
    }

    /**
     * @param Landlord $landlord
     *
     * @return Unit
     */
    public function setLandlord(Landlord $landlord)
    {
        $this->landlord = $landlord;

        return $this;
    }

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
     * @return Unit
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return string
     */
    public function getPayBy()
    {
        return $this->payBy;
    }

    /**
     * @param string $payBy
     *
     * @return Unit
     */
    public function setPayBy($payBy)
    {
        $this->payBy = $payBy;

        return $this;
    }

    /**
     * Get User.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return Resident[]|ArrayCollection
     */
    public function getResidents()
    {
        return $this->residents;
    }

    /**
     * @return ClientUnit[]|ArrayCollection
     */
    public function getClientUnits()
    {
        return $this->clientUnits;
    }
}
