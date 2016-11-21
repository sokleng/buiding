<?php

namespace CondoBundle\Entity;

use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityId;
use CondoBundle\Traits\HasEntityName;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use ProjectRealtyBundle\Entity\CondominiumProject;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * District.
 *
 * @ORM\Table(name="district")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\DistrictRepository")
 */
class District
{
    use HasEntityId;
    use HasEntityCreationDate;
    use HasEntityName;

    public function __construct()
    {
        $this->creationDate = new DateTime();
        $this->projects = new ArrayCollection();
    }

    /**
     * @var City
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\City",
     *     fetch="EAGER"
     * )
     */
    private $city;

    /**
     * @var CondominiumProject[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity="ProjectRealtyBundle\Entity\CondominiumProject",
     *      mappedBy="district"
     * )
     */
    private $projects;

    public function __toString()
    {
        return $this->name;
    }

    //region Accessors

    /**
     * @return City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param City $city
     *
     * @return District
     */
    public function setCity(City $city)
    {
        $this->city = $city;

        return $this;
    }

    //endregion
}
