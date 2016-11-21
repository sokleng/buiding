<?php

namespace RealtyCompanyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CondoBundle\Entity\Contact;
use Doctrine\Common\Collections\ArrayCollection;
use ProjectRealtyBundle\Entity\ProjectUnit;
use CondoBundle\Entity\DatabaseFile;

/**
 * CompanyContact.
 *
 * @ORM\Table(name="company_contact")
 * @ORM\Entity(repositoryClass="RealtyCompanyBundle\Repository\CompanyContactRepository")
 */
class CompanyContact extends Contact
{
    public function __construct()
    {
        parent::__construct();
        $this->realtyCompanies = new ArrayCollection();
        $this->projectUnits = new ArrayCollection();
        $this->databaseFiles = new ArrayCollection();
    }

    /**
     * @var ProjectUnit[]|ArrayCollection
     *
     * @ORM\ManyToMany(
     *     targetEntity="ProjectRealtyBundle\Entity\ProjectUnit",
     *     fetch="EAGER",
     *     mappedBy="contacts"
     * )
     */
    private $projectUnits;

    /**
     * @var RealtyCompany[]|ArrayCollection
     *
     * @ORM\ManyToMany(
     *     targetEntity="RealtyCompanyBundle\Entity\RealtyCompany",
     *     fetch="EAGER",
     *     mappedBy="contacts"
     * )
     */
    private $realtyCompanies;

    /**
     * @var DatabaseFile[]|ArrayCollection
     * @ORM\JoinColumn(nullable=true)
     * @ORM\OneToMany(
     *      targetEntity="CondoBundle\Entity\DatabaseFile",
     *      mappedBy="contact"
     *  )
     */
    private $databaseFiles;

    /**
     * @return ArrayCollection|ProjectUnit[]
     */
    public function getProjectUnits()
    {
        return $this->projectUnits;
    }

    /**
     * @return ArrayCollection|RealtyCompany[]
     */
    public function getRealtyCompanies()
    {
        return $this->realtyCompanies;
    }

    /**
     * @return ArrayCollection|DatabaseFiles[]
     */
    public function getDatabaseFiles()
    {
        return $this->databaseFiles;
    }
}
