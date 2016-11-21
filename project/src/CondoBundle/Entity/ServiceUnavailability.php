<?php

namespace CondoBundle\Entity;

use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityEnabled;
use CondoBundle\Traits\HasEntityId;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents and exceptional discontinuation of a service during a range of time,
 * Those times have precedence over service availabilities.
 *
 * @ORM\Table(name="service_unavailability")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\ServiceUnavailabilityRepository")
 */
class ServiceUnavailability
{
    use HasEntityId;
    use HasEntityCreationDate;
    use HasEntityEnabled;

    public function __construct()
    {
        $this->creationDate = new DateTime();

        // Defaults date
        $this->startDateTime = (new DateTime('TOMORROW'))
            ->add(\DateInterval::createFromDateString('12 hours'));
        $this->endDateTime = (new DateTime('TOMORROW'))
            ->add(\DateInterval::createFromDateString('36 hours'));
    }

    /**
     * The start date and time of the unavailability range.
     *
     * @var DateTime
     *
     * @ORM\Column(
     *     nullable=false,
     *     type="datetimetz"
     * )
     */
    private $startDateTime;

    /**
     * The end date and time of the unavailability range.
     *
     * @var DateTime
     *
     * @ORM\Column(
     *     nullable=false,
     *     type="datetimetz"
     * )
     */
    private $endDateTime;

    /**
     * @var Service
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\Service",
     *     inversedBy="unavailabilities"
     * )
     */
    private $service;

    public function isDateInRange(DateTime $dateTime)
    {
        if (!$this->isEnabled()) {
            return false;
        }

        return $dateTime >= $this->startDateTime && $dateTime <= $this->endDateTime;
    }

    //region Accessors
    /**
     * @return DateTime
     */
    public function getStartDateTime()
    {
        return $this->startDateTime;
    }

    /**
     * @param DateTime $startDateTime
     *
     * @return ServiceUnavailability
     */
    public function setStartDateTime(DateTime $startDateTime)
    {
        $this->startDateTime = $startDateTime;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getEndDateTime()
    {
        return $this->endDateTime;
    }

    /**
     * @param DateTime $endDateTime
     *
     * @return ServiceUnavailability
     */
    public function setEndDateTime(DateTime $endDateTime)
    {
        $this->endDateTime = $endDateTime;

        return $this;
    }

    /**
     * @return Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param Service $service
     *
     * @return ServiceUnavailability
     */
    public function setService(Service $service)
    {
        $this->service = $service;

        return $this;
    }
    //endregion
}
