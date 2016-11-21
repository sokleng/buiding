<?php

namespace CondoBundle\Entity;

use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityEnabled;
use CondoBundle\Traits\HasEntityId;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use UnexpectedValueException;

/**
 * Represents a service opening time,
 * It may be overridden by a ServiceClosingTime.
 *
 * @ORM\Table(name="service_availability")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\ServiceAvailabilityRepository")
 */
class ServiceAvailability
{
    use HasEntityId;
    use HasEntityCreationDate;
    use HasEntityEnabled;

    public function __construct()
    {
        $this->creationDate = new DateTime();
        $this->enabled = false;
    }

    /**
     * The opening time in minutes.
     * e.g.: 7am is stored as 7*60 = 420.
     * Ranges between 0 (00:00) to 1439 (23:59).
     *
     * @var int
     *
     * @ORM\Column(type="smallint")
     * @Assert\Range(min="0", max="1439")
     */
    private $openingTime;

    /**
     * The closing time in minutes.
     *
     * @var int
     *
     * @ORM\Column(type="smallint")
     * @Assert\Range(min="0", max="1439")
     */
    private $closingTime;

    /**
     * The day of the week for this opening time.
     *
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $dayOfTheWeekStart;

    /**
     * The day of the week for this opening time.
     * Ranges between 1 (Monday) and 7 (Sunday).
     *
     * @var int
     *
     * @ORM\Column(type="smallint")
     * @Assert\Range(min="1", max="7")
     */
    private $dayOfTheWeekEnd;

    /**
     * @var Service
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\Service",
     *     inversedBy="availabilities"
     * )
     */
    private $service;

    /**
     * Checks if a given datetime is included into the current availability range.
     * This will return false if that availability is disabled.
     *
     * @param DateTime $dateTime
     *
     * @return bool
     */
    public function isDateInRange(DateTime $dateTime)
    {
        if (!$this->isEnabled()) {
            return false;
        }
        $timestamp = $dateTime->getTimestamp();

        // Checking day of the week.
        $day = date('N', $timestamp);
        if ($day < $this->dayOfTheWeekStart || $day > $this->dayOfTheWeekEnd) {
            return false;
        }

        // Checking time
        $minutes = date('H', $timestamp) * 60 + date('i', $timestamp);
        if ($minutes < $this->openingTime || $minutes > $this->closingTime) {
            return false;
        }

        return true;
    }

    /**
     * Sets the opening-closing time range.
     * Times are set as integers representing a total number of minutes from midnight (0).
     *
     * @param int $openingTime
     * @param int $closingTime
     */
    public function setTimeRange($openingTime, $closingTime)
    {
        if ($closingTime <= $openingTime) {
            throw new UnexpectedValueException(
                'Closing time can not be the same or before the opening time'
            );
        }

        $this->openingTime = $openingTime;
        $this->closingTime = $closingTime;
    }

    /**
     * Sets the start-end day range.
     * Days are represented by integers from 1 to 7.
     *
     * @param int $rangeDayStart
     * @param int $rangeDayEnd
     *
     * @throws UnexpectedValueException if the end is the same or before the start
     */
    public function setDayRange($rangeDayStart, $rangeDayEnd)
    {
        if ($rangeDayEnd <= $rangeDayStart) {
            throw new UnexpectedValueException(
                'End range day can not be the same of before the start range day.'
            );
        }

        $this->dayOfTheWeekStart = $rangeDayStart;
        $this->dayOfTheWeekEnd = $rangeDayEnd;
    }

    //region Accessors
    /**
     * @return int
     */
    public function getOpeningTime()
    {
        return $this->openingTime;
    }

    /**
     * @param int $time
     *
     * @return ServiceAvailability
     */
    public function setOpeningTime($time)
    {
        $this->openingTime = $time;

        return $this;
    }

    /**
     * @return int
     */
    public function getClosingTime()
    {
        return $this->closingTime;
    }

    /**
     * @param int $time
     *
     * @return ServiceAvailability
     */
    public function setClosingTime($time)
    {
        $this->closingTime = $time;

        return $this;
    }

    /**
     * @return int
     */
    public function getDayOfTheWeekStart()
    {
        return $this->dayOfTheWeekStart;
    }

    /**
     * @return int
     */
    public function getDayOfTheWeekEnd()
    {
        return $this->dayOfTheWeekEnd;
    }

    /**
     * @return Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param int $dayOfTheWeekStart
     *
     * @return ServiceAvailability
     */
    public function setDayOfTheWeekStart($dayOfTheWeekStart)
    {
        $this->dayOfTheWeekStart = $dayOfTheWeekStart;

        return $this;
    }

    /**
     * @param int $dayOfTheWeekEnd
     *
     * @return ServiceAvailability
     */
    public function setDayOfTheWeekEnd($dayOfTheWeekEnd)
    {
        $this->dayOfTheWeekEnd = $dayOfTheWeekEnd;

        return $this;
    }

    /**
     * @param Service $service
     *
     * @return ServiceAvailability
     */
    public function setService(Service $service)
    {
        $this->service = $service;

        return $this;
    }

    //endregion
}
