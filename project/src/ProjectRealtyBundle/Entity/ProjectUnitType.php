<?php

namespace ProjectRealtyBundle\Entity;

use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityId;
use CondoBundle\Traits\HasEntityUnitTypeFields;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectUnitType.
 *
 * @ORM\Table(name="project_unit_type")
 * @ORM\Entity(repositoryClass="ProjectRealtyBundle\Repository\ProjectUnitTypeRepository")
 */
class ProjectUnitType
{
    use HasEntityId;
    use HasEntityCreationDate;
    use HasEntityUnitTypeFields;

    public function __construct()
    {
        $this->creationDate = new DateTime();
        $this->projectUnits = new ArrayCollection();
    }

    /**
     * @var CondominiumProject
     *
     * @ORM\ManyToOne(
     *     targetEntity="ProjectRealtyBundle\Entity\CondominiumProject",
     *     inversedBy="unitTypes"
     * )
     */
    private $project;

    /**
     * @var ProjectUnit
     *
     * @ORM\OneToMany(
     *     targetEntity="ProjectRealtyBundle\Entity\ProjectUnit",
     *     mappedBy="type"
     * )
     */
    private $projectUnits;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $commonAreaSize;

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
     * @return CondominiumProject
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param CondominiumProject $project
     *
     * @return ProjectUnitType
     */
    public function setProject(CondominiumProject $project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return ProjectUnit
     */
    public function getProjectUnits()
    {
        return $this->projectUnits;
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
     * @return ProjectUnitType
     */
    public function setCommonAreaSize($commonAreaSize)
    {
        $this->commonAreaSize = $commonAreaSize;

        return $this;
    }
}
