<?php

namespace DeveloperBundle\Entity;

use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityDescription;
use CondoBundle\Traits\HasEntityId;
use CondoBundle\Traits\HasEntityManagers;
use CondoBundle\Traits\HasEntityName;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use DateTime;
use ProjectRealtyBundle\Entity\CondominiumProject;
use RealtyCompanyBundle\Entity\RealtyCompany;

/**
 * Developer.
 *
 * @ORM\Table(name="developer")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\DeveloperRepository")
 */
class Developer
{
    use HasEntityId;
    use HasEntityName;
    use HasEntityDescription;
    use HasEntityManagers;
    use HasEntityCreationDate;

    public function __construct()
    {
        $this->creationDate = new DateTime();
        $this->realtyCompanies = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->managers = new ArrayCollection();
    }

    /**
     * @var RealtyCompany[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity="RealtyCompanyBundle\Entity\RealtyCompany",
     *      mappedBy="developer"
     * )
     */
    private $realtyCompanies;

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return ArrayCollection|RealtyCompany[]
     */
    public function getRealtyCompanies()
    {
        return $this->realtyCompanies;
    }

    /**
     * @var CondominiumProject[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity="ProjectRealtyBundle\Entity\CondominiumProject",
     *      mappedBy="developer"
     * )
     */
    private $projects;
    /**
     * @return CondominiumProject[]|ArrayCollection
     */
    public function getProjects()
    {
        return $this->projects;
    }
}
