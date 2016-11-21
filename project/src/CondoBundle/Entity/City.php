<?php

namespace CondoBundle\Entity;

use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityId;
use CondoBundle\Traits\HasEntityName;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * City.
 *
 * @ORM\Table(name="city")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\CityRepository")
 */
class City
{
    use HasEntityId;
    use HasEntityCreationDate;
    use HasEntityName;

    public function __construct()
    {
        $this->creationDate = new DateTime();
    }

    /**
     * The country associated with that city.
     *
     * @var Country
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\Country",
     *     fetch="EAGER"
     * )
     */
    private $country;

    //region Accessors

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param Country $country
     *
     * @return City
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    //endregion
}
