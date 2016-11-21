<?php

namespace RealtyCompanyBundle\Entity;

use CondoBundle\Traits\HasEntityContactNumber;
use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityDescription;
use CondoBundle\Traits\HasEntityId;
use CondoBundle\Traits\HasEntityManagers;
use CondoBundle\Traits\HasEntityName;
use Doctrine\ORM\Mapping as ORM;
use ProjectRealtyBundle\Entity\CondominiumProject;
use DeveloperBundle\Entity\Developer;
use Doctrine\Common\Collections\ArrayCollection;
use DateTime;

/**
 * RealtyCompany.
 *
 * @ORM\Table(name="realty_company")
 * @ORM\Entity(repositoryClass="RealtyCompanyBundle\Repository\RealtyCompanyRepository")
 */
class RealtyCompany
{
    use HasEntityId;
    use HasEntityName;
    use HasEntityDescription;
    use HasEntityCreationDate;
    use HasEntityManagers;
    use HasEntityContactNumber;

    public function __construct()
    {
        $this->creationDate = new DateTime();
        $this->projects = new ArrayCollection();
        $this->managers = new ArrayCollection();
        $this->contacts = new ArrayCollection();
        $this->developerPayments = new ArrayCollection();
    }

    /**
     * @var CondominiumProject[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="ProjectRealtyBundle\Entity\CondominiumProject",
     *     mappedBy="realtyCompany"
     * )
     */
    private $projects;

    /**
     * @var RealtyCompany[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="DeveloperBundle\Entity\DeveloperPayment",
     *     mappedBy="realtyCompany"
     * )
     */
    private $developerPayments;

    /**
     * @var CompanyContact[]|ArrayCollection
     *
     * @ORM\ManyToMany(
     *     targetEntity="RealtyCompanyBundle\Entity\CompanyContact",
     *     fetch="EAGER",
     *     inversedBy="realtyCompanies"
     * )
     */
    private $contacts;

    /**
     * @var Developer
     * @ORM\ManyToOne(
     *     targetEntity="DeveloperBundle\Entity\Developer",
     *     inversedBy="realtyCompanies"
     * )
     */
    private $developer;

    /**
     * @return ArrayCollection|CondominiumProject[]
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * @return Developer
     */
    public function getDeveloper()
    {
        return $this->developer;
    }

    /**
     * @return ArrayCollection|CompanyContact[]
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * @return ArrayCollection|DeveloperPayment[]
     */
    public function getDeveloperPayments()
    {
        return $this->developerPayments;
    }
}
