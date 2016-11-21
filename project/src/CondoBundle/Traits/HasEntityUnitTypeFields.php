<?php

namespace CondoBundle\Traits;

use Doctrine\ORM\Mapping as ORM;

trait HasEntityUnitTypeFields
{
    /**
     * The unit type code / unique identifier used by the condo.
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $code;

    /**
     * The unit size in square meters.
     *
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $size;

    /**
     * The total number of rooms.
     *
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $roomCount;

    /**
     * The total number of bedrooms.
     *
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $bedroomCount;

    /**
     * The total number of bathrooms.
     *
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $bathroomCount;

    /**
     * An optional / Additional description to the unit type.
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $description;

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return float
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param float $size
     *
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return int
     */
    public function getRoomCount()
    {
        return $this->roomCount;
    }

    /**
     * @param int $roomCount
     *
     * @return $this
     */
    public function setRoomCount($roomCount)
    {
        $this->roomCount = $roomCount;

        return $this;
    }

    /**
     * @return int
     */
    public function getBedroomCount()
    {
        return $this->bedroomCount;
    }

    /**
     * @param int $bedroomCount
     *
     * @return $this
     */
    public function setBedroomCount($bedroomCount)
    {
        $this->bedroomCount = $bedroomCount;

        return $this;
    }

    /**
     * @return int
     */
    public function getBathroomCount()
    {
        return $this->bathroomCount;
    }

    /**
     * @param int $bathroomCount
     *
     * @return $this
     */
    public function setBathroomCount($bathroomCount)
    {
        $this->bathroomCount = $bathroomCount;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
}
