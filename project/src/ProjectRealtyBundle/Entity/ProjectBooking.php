<?php

namespace ProjectRealtyBundle\Entity;

use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityId;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use RealtyCompanyBundle\Entity\CompanyContact;

/**
 * ProjectBooking.
 *
 * @ORM\Table(name="project_booking")
 * @ORM\Entity(repositoryClass="ProjectRealtyBundle\Repository\ProjectBookingRepository")
 */
class ProjectBooking
{
    use HasEntityId;
    use HasEntityCreationDate;

    public function __construct()
    {
        $this->creationDate = new DateTime();
    }

    /**
     * @var ProjectUnit
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *     targetEntity="ProjectRealtyBundle\Entity\ProjectUnit"
     * )
     */
    private $unit;

    /**
     * @var CompanyContact
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *   targetEntity="RealtyCompanyBundle\Entity\CompanyContact"
     * )
     */
    private $contact;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $seller;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $askingPrice;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $discount;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $finalPrice;

    /**
     * @return ProjectUnit
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param ProjectUnit $unit
     *
     * @return ProjectBooking
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return CompanyContact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param CompanyContact $contact
     *
     * @return ProjectBooking
     */
    public function setContact(CompanyContact $contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * @return string
     */
    public function getSeller()
    {
        return $this->seller;
    }

    /**
     * @param string $seller
     *
     * @return ProjectBooking
     */
    public function setSeller($seller)
    {
        $this->seller = $seller;

        return $this;
    }

    /**
     * @return float
     */
    public function getAskingPrice()
    {
        return $this->askingPrice;
    }

    /**
     * @param float $askingPrice
     *
     * @return ProjectBooking
     */
    public function setAskingPrice($askingPrice)
    {
        $this->askingPrice = $askingPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param float $discount
     *
     * @return ProjectBooking
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * @return float
     */
    public function getFinalPrice()
    {
        return $this->finalPrice;
    }

    /**
     * @param float $finalPrice
     *
     * @return ProjectBooking
     */
    public function setFinalPrice($finalPrice)
    {
        $this->finalPrice = $finalPrice;

        return $this;
    }
}
