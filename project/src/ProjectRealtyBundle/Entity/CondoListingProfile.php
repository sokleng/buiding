<?php

namespace ProjectRealtyBundle\Entity;

use CondoBundle\Traits\HasEntityId;
use CondoBundle\Traits\HasEntityDescription;
use CondoBundle\Entity\DatabaseFile;
use Doctrine\ORM\Mapping as ORM;

/**
 * CondoListingProfile.
 *
 * @ORM\Table(name="condo_listing_profile")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\Entity
 * @ORM\DiscriminatorMap({
 *    "condoProjectListingProfile" = "CondoProjectListingProfile"
 * })
 */
abstract class CondoListingProfile
{
    use HasEntityId;
    use HasEntityDescription;

    public function __construct()
    {
        $this->published = false;
    }

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $published;

    /**
     * @var DatabaseFile
     *
     * @ORM\JoinColumn(nullable=true)
     * @ORM\ManyToOne(targetEntity="CondoBundle\Entity\DatabaseFile")
     */
    private $databaseFile;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $latitude;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"default"=0})
     */
    private $totalUnit;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $longitude;

    /**
     * @var int
     *
     * @ORM\Column(type="integer" , options={"default"=0})
     */
    private $type;

    /**
     * @param int $type
     *
     * @return CondoListingProfile
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * @param bool $published
     *
     * @return CondoListingProfile
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @param int $totalUnit
     *
     * @return CondoListingProfile
     */
    public function setTotalUnit($totalUnit)
    {
        $this->totalUnit = $totalUnit;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalUnit()
    {
        return $this->totalUnit;
    }

    /**
     * @return DatabaseFile
     */
    public function getDatabaseFile()
    {
        return $this->databaseFile;
    }

    /**
     * @param DatabaseFile $databaseFile
     *
     * @return CondoListingProfile
     */
    public function setDatabaseFile(DatabaseFile $databaseFile)
    {
        $this->databaseFile = $databaseFile;

        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     *
     * @return CondoListingProfile
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     *
     * @return CondoListingProfile
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }
}
