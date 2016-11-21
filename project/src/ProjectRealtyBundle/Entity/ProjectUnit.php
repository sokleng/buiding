<?php

namespace ProjectRealtyBundle\Entity;

use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityId;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use RealtyCompanyBundle\Entity\CompanyContact;

/**
 * ProjectUnit.
 *
 * @ORM\Table(name="project_unit")
 * @ORM\Entity(repositoryClass="ProjectRealtyBundle\Repository\ProjectUnitRepository")
 */
class ProjectUnit
{
    use HasEntityId;
    use HasEntityCreationDate;

    public function __construct()
    {
        $this->creationDate = new DateTime();
        $this->contacts = new ArrayCollection();
        $this->isAvailable = true;
        $this->payments = new ArrayCollection();
    }

    /**
     * @var ProjectUnitType
     *
     * @ORM\ManyToOne(
     *     targetEntity="ProjectRealtyBundle\Entity\ProjectUnitType",
     *     fetch="EAGER",
     *     inversedBy="projectUnits"
     * )
     */
    private $type;

    /**
     * @var ProjectUnitStatus
     *
     * @ORM\ManyToOne(
     *     targetEntity="ProjectRealtyBundle\Entity\ProjectUnitStatus",
     *     fetch="EAGER"
     * )
     */
    private $status;

    /**
     * @var CompanyContact[]|ArrayCollection
     *
     * @ORM\ManyToMany(
     *     targetEntity="RealtyCompanyBundle\Entity\CompanyContact",
     *     fetch="EAGER",
     *     inversedBy="projectUnits"
     * )
     */
    private $contacts;

    /**
     * @var CondominiumProject
     *
     * @ORM\ManyToOne(
     *     targetEntity="ProjectRealtyBundle\Entity\CondominiumProject",
     *     inversedBy="units"
     * )
     */
    private $project;

    /**
     * The Unit Floor.
     *
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $floor;

    /**
     * The Unit Price.
     *
     * @var float
     *
     * @ORM\Column(type="float", nullable=false)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false, unique=true)
     */
    private $roomNumber;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean" , options={"default": true})
     */
    private $isAvailable;

    /**
     * @var ArrayCollection|ProjectPayment[]
     *
     * @ORM\OneToMany(
     *     targetEntity="ProjectRealtyBundle\Entity\ProjectPayment",
     *     mappedBy="unit"
     * )
     */
    private $payments;

    /**
     * @return bool
     */
    public function isAvailable()
    {
        return $this->isAvailable;
    }

    /**
     * @param bool $isAvailable
     *
     * @return ProjectUnit
     */
    public function setIsAvailable($isAvailable)
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    public function __toString()
    {
        return $this->roomNumber.' ('.$this->type->getCode().')';
    }

    /**
     * @return ProjectUnitType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param ProjectUnitType $type
     *
     * @return ProjectUnit
     */
    public function setType(ProjectUnitType $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return ProjectUnitStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param ProjectUnitStatus $status
     *
     * @return ProjectUnit
     */
    public function setStatus(ProjectUnitStatus $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return ArrayCollection|CompanyContact[]
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * @return CondominiumProject
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param CondominiumProject $project
     *
     * @return ProjectUnit
     */
    public function setProject($project)
    {
        $this->project = $project;

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
     * @return ProjectUnit
     */
    public function setFloor($floor)
    {
        $this->floor = $floor;

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
     * @return ProjectUnit
     */
    public function setPrice($price)
    {
        $this->price = $price;

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
     * @return ProjectUnit
     */
    public function setRoomNumber($roomNumber)
    {
        $this->roomNumber = $roomNumber;

        return $this;
    }
}
