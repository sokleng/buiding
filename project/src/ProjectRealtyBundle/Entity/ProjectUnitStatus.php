<?php

namespace ProjectRealtyBundle\Entity;

use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityEnabled;
use CondoBundle\Traits\HasEntityId;
use CondoBundle\Traits\HasEntityName;
use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectUnitStatus.
 *
 * @ORM\Table(name="project_unit_status")
 * @ORM\Entity(repositoryClass="ProjectRealtyBundle\Repository\ProjectUnitStatusRepository")
 */
class ProjectUnitStatus
{
    use HasEntityId;
    use HasEntityCreationDate;
    use HasEntityName;
    use HasEntityEnabled;

    public function __construct()
    {
        $this->creationDate = new \DateTime();
        $this->closedStatus = false;
    }

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $closedStatus;

    /**
     * @var CondominiumProject
     *
     * @ORM\ManyToOne(
     *     targetEntity="ProjectRealtyBundle\Entity\CondominiumProject",
     *     inversedBy="unitStatuses"
     * )
     */
    private $project;

    /**
     * @return bool
     */
    public function isClosedStatus()
    {
        return $this->closedStatus;
    }

    /**
     * @param bool $closedStatus
     *
     * @return ProjectUnitStatus
     */
    public function setClosedStatus($closedStatus)
    {
        $this->closedStatus = $closedStatus;

        return $this;
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
     * @return ProjectUnitStatus
     */
    public function setProject(CondominiumProject $project)
    {
        $this->project = $project;

        return $this;
    }
}
