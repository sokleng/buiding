<?php

namespace ProjectRealtyBundle\Entity;

use CondoBundle\Traits\HasEntityAddress;
use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityDescription;
use CondoBundle\Traits\HasEntityId;
use CondoBundle\Traits\HasEntityManagers;
use CondoBundle\Traits\HasEntityName;
use CondoBundle\Traits\HasEntityContactNumber;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use RealtyCompanyBundle\Entity\RealtyCompany;
use DeveloperBundle\Entity\Developer;
use CondoBundle\Entity\District;

/**
 * CondominiumProject.
 *
 * @ORM\Table(name="condominium_project")
 * @ORM\Entity(repositoryClass="ProjectRealtyBundle\Repository\CondominiumProjectRepository")
 */
class CondominiumProject
{
    use HasEntityId;
    use HasEntityCreationDate;
    use HasEntityName;
    use HasEntityAddress;
    use HasEntityDescription;
    use HasEntityManagers;
    use HasEntityContactNumber;

    public function __construct()
    {
        $this->creationDate = new DateTime();
        $this->managers = new ArrayCollection();
        $this->unitStatuses = new ArrayCollection();
        $this->units = new ArrayCollection();
        $this->unitTypes = new ArrayCollection();
    }

    /**
     * @var ProjectUnit[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="ProjectRealtyBundle\Entity\ProjectUnit",
     *     mappedBy="project"
     * )
     */
    private $units;

    /**
     * @var ProjectUnitStatus[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="ProjectRealtyBundle\Entity\ProjectUnitStatus",
     *     mappedBy="project",
     *     fetch="EAGER"
     * )
     */
    private $unitStatuses;

    /**
     * @var ProjectUnitType[]|ArrayCollection
     *
     * @ORM\OrderBy({"code" = "DESC"})
     * @ORM\OneToMany(
     *     targetEntity="ProjectUnitType",
     *     mappedBy="project",
     *     fetch="EAGER",
     * )
     */
    private $unitTypes;

    /**
     * @var RealtyCompany
     * @ORM\ManyToOne(
     *     targetEntity="RealtyCompanyBundle\Entity\RealtyCompany",
     *     inversedBy="projects"
     * )
     */
    private $realtyCompany;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $contactName;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $floorCount;

    /**
     * @var Developer
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *     targetEntity="DeveloperBundle\Entity\Developer",
     *     inversedBy="projects",
     *     fetch="EAGER"
     * )
     */
    private $developer;

    /**
     * @var CondoProjectListingProfile
     *
     * @ORM\OneToOne(
     *     targetEntity="ProjectRealtyBundle\Entity\CondoProjectListingProfile",
     *     mappedBy="project",
     *     fetch="EAGER"
     * )
     */
    private $projectListing;

    /**
     * @var District
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\District",
     *     inversedBy="projects",
     *     fetch="EAGER"
     * )
     */
    private $district;

    /**
     * @return ArrayCollection|ProjectUnit[]
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * @return ArrayCollection|ProjectUnitStatus[]
     */
    public function getUnitStatuses()
    {
        return $this->unitStatuses;
    }

    /**
     * @return string
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * @param string $contactName
     *
     * @return CondominiumProject
     */
    public function setContactName($contactName)
    {
        $this->contactName = $contactName;

        return $this;
    }

    /**
     * @return ArrayCollection|ProjectUnitType[]
     */
    public function getUnitTypes()
    {
        return $this->unitTypes;
    }

    /**
     * @return int
     */
    public function getFloorCount()
    {
        return $this->floorCount;
    }

    /**
     * @param int $floorCount
     *
     * @return CondominiumProject
     */
    public function setFloorCount($floorCount)
    {
        $this->floorCount = $floorCount;

        return $this;
    }

    /**
     * @return RealtyCompany
     */
    public function getRealtyCompany()
    {
        return $this->realtyCompany;
    }

    /**
     * @param RealtyCompany $realtyCompany
     *
     * @return CondominiumProject
     */
    public function setRealtyCompany(RealtyCompany $realtyCompany)
    {
        $this->realtyCompany = $realtyCompany;

        return $this;
    }

    /**
     * @return Developer
     */
    public function getDeveloper()
    {
        return $this->developer;
    }

    /**
     * @param Developer $developer
     *
     * @return CondominiumProject
     */
    public function setDeveloper(Developer $developer)
    {
        $this->developer = $developer;

        return $this;
    }

    /**
     * @return CondoProjectListingProfile
     */
    public function getProjectListing()
    {
        return $this->projectListing;
    }

    /**
     * @param CondoProjectListingProfile $projectListing
     *
     * @return CondominiumProject
     */
    public function setProjectListing(CondoProjectListingProfile $projectListing)
    {
        $this->projectListing = $projectListing;

        return $this;
    }

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
     * @return CondominiumProject
     */
    public function setDistrict(District $district)
    {
        $this->district = $district;

        return $this;
    }
}
