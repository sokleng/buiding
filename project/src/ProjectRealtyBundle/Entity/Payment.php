<?php

namespace ProjectRealtyBundle\Entity;

use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityDescription;
use CondoBundle\Traits\HasEntityId;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use RealtyCompanyBundle\Entity\CompanyContact;
use ProjectRealtyBundle\Constant\PaymentStatus;

/**
 * Payment.
 *
 * @ORM\Table(name="payments")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\Entity
 * @ORM\DiscriminatorMap({
 *    "projectPayment" = "ProjectPayment",
 *    "developerPayment" = "DeveloperBundle\Entity\DeveloperPayment"
 *  })
 */
abstract class Payment
{
    use HasEntityId;
    use HasEntityCreationDate;
    use HasEntityDescription;

    public function __construct()
    {
        $this->creationDate = new DateTime();
        $this->received = false;
        $this->status = PaymentStatus::DRAFT;
    }

    /**
     * @var CompanyContact
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *     targetEntity="RealtyCompanyBundle\Entity\CompanyContact"
     * )
     */
    private $contact;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $receiver;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $paymentMethod;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $received;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $paymentDate;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $status;

    /**
     * @param DateTime $paymentDate
     *
     * @return Payment
     */
    public function setPaymentDate(DateTime $paymentDate)
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getPaymentDate()
    {
        return $this->paymentDate;
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
     * @return Payment
     */
    public function setContact(CompanyContact $contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * @return string
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @param string $receiver
     *
     * @return Payment
     */
    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;

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
     * @return Payment
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     *
     * @return Payment
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return bool
     */
    public function isReceived()
    {
        return $this->received;
    }

    /**
     * @param bool $received
     *
     * @return Payment
     */
    public function setReceived($received)
    {
        $this->received = $received;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return Payment
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }
}
