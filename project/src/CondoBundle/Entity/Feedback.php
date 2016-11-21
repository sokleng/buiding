<?php

namespace CondoBundle\Entity;

use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityId;
use CondoBundle\Traits\HasEntityUser;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Feedback.
 *
 * @ORM\Table(name="feedback")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\FeedbackRepository")
 */
class Feedback
{
    use HasEntityId;
    use HasEntityUser;
    use HasEntityCreationDate;

    public function __construct()
    {
        $this->creationDate = new DateTime();
        $this->read = false;
    }

    /**
     * @var Condominium
     *
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\Condominium",
     *     fetch="EAGER"
     * )
     */
    private $condominium;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    private $message;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $read;

    /**
     * @var int
     *
     * @ORM\Column(name="rate", type="integer", nullable=true)
     */
    private $rate;

    /**
     * return rate.
     */
    public function isVeryLow()
    {
        return $this->rate === 1;
    }

    /**
     * return rate.
     */
    public function isLow()
    {
        return $this->rate === 2;
    }

    /**
     * return rate.
     */
    public function isAverage()
    {
        return $this->rate === 3;
    }

    /**
     * return rate.
     */
    public function isHigh()
    {
        return $this->rate === 4;
    }

    /**
     * return rate.
     */
    public function isVeryHigh()
    {
        return $this->rate === 5;
    }

    /**
     * @return int
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param int $rate
     *
     * @return Feedback
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @return Condominium
     */
    public function getCondominium()
    {
        return $this->condominium;
    }

    /**
     * @param Condominium $condominium
     *
     * @return Feedback
     */
    public function setCondominium(Condominium $condominium)
    {
        $this->condominium = $condominium;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return Feedback
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRead()
    {
        return $this->read;
    }

    /**
     * @param bool $read
     *
     * @return Feedback
     */
    public function setRead($read)
    {
        $this->read = $read;

        return $this;
    }
}
