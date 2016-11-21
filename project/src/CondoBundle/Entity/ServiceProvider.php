<?php

namespace CondoBundle\Entity;

use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityDescription;
use CondoBundle\Traits\HasEntityId;
use CondoBundle\Traits\HasEntityManagers;
use CondoBundle\Traits\HasEntityCurrency;
use CondoBundle\Traits\HasEntityVat;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ServiceProvider.
 *
 * @ORM\Table(name="service_provider")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\ServiceProviderRepository")
 */
class ServiceProvider
{
    use HasEntityId;
    use HasEntityCreationDate;
    use HasEntityDescription;
    use HasEntityManagers;
    use HasEntityCurrency;
    use HasEntityVat;

    public function __construct()
    {
        $this->creationDate = new DateTime();
        $this->managers = new ArrayCollection();
    }

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $companyName;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $contactNumber;

    public function __toString()
    {
        return $this->getCompanyName();
    }

    //region Accessors
    /**
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @param string $companyName
     *
     * @return ServiceProvider
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * @return string
     */
    public function getContactNumber()
    {
        return $this->contactNumber;
    }

    /**
     * @param string $contactNumber
     *
     * @return ServiceProvider
     */
    public function setContactNumber($contactNumber)
    {
        $this->contactNumber = $contactNumber;

        return $this;
    }

    //endregion
}
