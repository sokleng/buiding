<?php

namespace CondoBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use WeBridge\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use CondoBundle\Traits\HasEntityPhone;
use CondoBundle\Traits\HasEntityPicture;
use CondoBundle\Traits\IsVatEntity;
use CondoBundle\Traits\HasEntityExchangeSetting;
use CondoBundle\Traits\HasEntityCurrency;

/**
 * ClientUnit.
 *
 * @ORM\Table(name="client_unit")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\ClientUnitRepository")
 */
class ClientUnit
{
    use HasEntityPhone;
    use HasEntityPicture;
    use IsVatEntity;
    use HasEntityCurrency;
    use HasEntityExchangeSetting;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @Column(type="string", nullable=true)
     */
    private $idCard;

    /**
     * @var DatabaseFile
     *
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\DatabaseFile",
     *     fetch="EAGER",
     *     cascade={"ALL"}
     * )
     */
    private $idCardPicture;

    /**
     * The ClientUnit startDate.
     *
     * @var datetime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * The ClientUnit endDate.
     *
     * @var datetime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * The ClientUnit paymentMethod.
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $paymentMethod;

    /**
     * The ClientUnit rentalPrice.
     *
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $rentalPrice;

    /**
     * The ClientUnit unitPrice.
     *
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $unitPrice;

    /**
     * The ClientUnit Amount.
     *
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $amount;

    /**
     * The unit owning that user.
     *
     * @var Unit
     *
     * @ORM\JoinColumn(nullable=true)
     * @ORM\ManyToOne(
     *      targetEntity="CondoBundle\Entity\Unit",
     *      inversedBy="clientUnits",
     *      fetch="EAGER"
     * )
     */
    private $unit;

    /**
     * @var User
     *
     * @ORM\JoinColumn(nullable=true)
     * @ORM\ManyToOne(
     *      targetEntity="WeBridge\UserBundle\Entity\User",
     *      fetch="EAGER"
     * )
     */
    private $user;

    /**
     * @var bool
     *
     * @ORM\Column(
     *     type="boolean",
     *     options={"default"=false}
     * )
     */
    private $isRunScheduleAuto;

    /**
     * @var bool
     *
     * @ORM\Column(
     *     type="boolean",
     *     options={"default"=false}
     * )
     */
    private $isSendInvoice;

    /**
     * @var bool
     *
     * @ORM\Column(
     *     type="boolean",
     *     options={"default"=false}
     * )
     */
    private $generatedInvoice;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $day;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $hour;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $nationality;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $address;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getIdCard()
    {
        return $this->idCard;
    }

    /**
     * @param string $idCard
     *
     * @return ClientUnit
     */
    public function setIdCard($idCard)
    {
        $this->idCard = $idCard;

        return $this;
    }

    /**
     * @return DatabaseFile
     */
    public function getIdCardPicture()
    {
        return $this->idCardPicture;
    }

    /**
     * @param DatabaseFile $IdCardPicture
     *
     * @return ClientUnit
     */
    public function setIdCardPicture(DatabaseFile $idCardPicture)
    {
        $this->idCardPicture = $idCardPicture;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param DateTime $startDate
     *
     * @return ClientUnit
     */
    public function setStartDate(DateTime $startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param DateTime $endDate
     *
     * @return ClientUnit
     */
    public function setEndDate(DateTime $endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @param string $paymentMethod
     *
     * @return ClientUnit
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * @return float
     */
    public function getRentalPrice()
    {
        return $this->rentalPrice;
    }

    /**
     * @param float $rentalPrice
     *
     * @return ClientUnit
     */
    public function setRentalPrice($rentalPrice)
    {
        $this->rentalPrice = $rentalPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * @param float $unitPrice
     *
     * @return ClientUnit
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     *
     * @return ClientUnit
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return Unit
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param Unit $unit
     *
     * @return ClientUnit
     */
    public function setUnit(Unit $unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return ClientUnit
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRunScheduleAuto()
    {
        return $this->isRunScheduleAuto;
    }

    /**
     * @param bool $isRunScheduleAuto
     *
     * @return ClientUnit
     */
    public function setIsRunScheduleAuto($isRunScheduleAuto)
    {
        $this->isRunScheduleAuto = $isRunScheduleAuto;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSendInvoice()
    {
        return $this->isSendInvoice;
    }

    /**
     * @param bool $isSendInvoice
     *
     * @return ClientUnit
     */
    public function setIsSendInvoice($isSendInvoice)
    {
        $this->isSendInvoice = $isSendInvoice;

        return $this;
    }

    /**
     * @return bool
     */
    public function generatedInvoice()
    {
        return $this->generatedInvoice;
    }

    /**
     * @param bool $generatedInvoice
     *
     * @return ClientUnit
     */
    public function setGeneratedInvoice($generatedInvoice)
    {
        $this->generatedInvoice = $generatedInvoice;

        return $this;
    }

    /**
     * @return string
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param string $day
     *
     * @return ClientUnit
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * @return string
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * @param string $hour
     *
     * @return ClientUnit
     */
    public function setHour($hour)
    {
        $this->hour = $hour;

        return $this;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     *
     * @return ClientUnit
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return string
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * @param string $nationality
     *
     * @return ClientUnit
     */
    public function setNationality($nationality)
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     *
     * @return $ClientUnit
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }
}
