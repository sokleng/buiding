<?php

namespace CondoBundle\Entity;

use CondoBundle\Traits\HasEntityId;
use CondoBundle\Traits\HasEntityTitle;
use CondoBundle\Traits\HasEntityCondominium;
use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityDescriptionNullable;
use CondoBundle\Traits\IsVatEntity;
use CondoBundle\Traits\HasEntitySubTotal;
use CondoBundle\Traits\HasEntityGrandTotal;
use CondoBundle\Traits\HasEntityCurrency;
use CondoBundle\Traits\HasEntityExchangeSetting;
use CondoBundle\Constant\InvoiceStatus;
use WeBridge\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * The base class for income and expend.
 *
 * @ORM\Table(name="invoice")
 * @ORM\InheritanceType(value="JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\InvoiceRepository")
 * @ORM\DiscriminatorMap({
 *     "income" = "Income",
 *     "expend" = "Expend"
 * })
 */
abstract class Invoice
{
    use HasEntityId;
    use HasEntityTitle;
    use HasEntityCondominium;
    use HasEntityCreationDate;
    use HasEntityDescriptionNullable;
    use HasEntityCurrency;
    use IsVatEntity;
    use HasEntitySubTotal;
    use HasEntityGrandTotal;
    use HasEntityExchangeSetting;

    public function __construct()
    {
        $this->creationDate = new DateTime();
        $this->status = InvoiceStatus::UNPAID;
    }

    /**
     * The Invoice discount.
     *
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $discount;

    /**
     * The IncomeAndExpend grandTotal.
     *
     * @var float
     *
     * @ORM\Column(type="float", nullable=false)
     */
    private $grandTotal;

    /**
     * The Invoice grandTotal amount in us.
     *
     * @var float
     *
     * @ORM\Column(type="float", nullable=false)
     */
    private $usdAmount;

    /**
     * @var int
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $status;

    /**
     * The Invoice creator.
     *
     * @var user
     *
     * @ORM\JoinColumn(nullable=true)
     * @ORM\ManyToOne(
     *     targetEntity="WeBridge\UserBundle\Entity\User"
     * )
     */
    private $createBy;

    /**
     * The Invoice paid date.
     *
     * @var DateTime
     *
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $paymentDate;

    /**
     * The user that mark invoice as paid.
     *
     * @var user
     *
     * @ORM\JoinColumn(nullable=true)
     * @ORM\ManyToOne(
     *     targetEntity="WeBridge\UserBundle\Entity\User"
     * )
     */
    private $markAsPaidBy;

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
     * @return Invoice
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * @return float
     */
    public function getGrandTotal()
    {
        return $this->grandTotal;
    }

    /**
     * @param float $grandTotal
     *
     * @return Invoice
     */
    public function setGrandTotal($grandTotal)
    {
        $this->grandTotal = $grandTotal;

        return $this;
    }

    /**
     * @return float
     */
    public function getUsdAmount()
    {
        return $this->usdAmount;
    }

    /**
     * @param float $usdAmount
     *
     * @return Invoice
     */
    public function setUsdAmount($usdAmount)
    {
        $this->usdAmount = $usdAmount;

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
     * @return Invoice
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return User $createBy
     */
    public function getCreateBy()
    {
        return $this->createBy;
    }

    /**
     * @param User $createBy
     *
     * @return Invoice
     */
    public function setCreateBy(User $createBy)
    {
        $this->createBy = $createBy;

        return $this;
    }

    /**
     * @return DateTime $paymentDate
     */
    public function getPaymentDate()
    {
        return $this->paymentDate;
    }

    /**
     * @param DateTime $paymentDate
     *
     * @return Invoice
     */
    public function setPaymentDate(DateTime $paymentDate)
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    /**
     * @return User $markAsPaidBy
     */
    public function getMarkAsPaidBy()
    {
        return $this->markAsPaidBy;
    }

    /**
     * @param User $markAsPaidBy
     *
     * @return Invoice
     */
    public function setMarkAsPaidBy(User $markAsPaidBy)
    {
        $this->markAsPaidBy = $markAsPaidBy;

        return $this;
    }

    /**
     * return status.
     */
    public function isPaid()
    {
        return $this->status === InvoiceStatus::PAID;
    }

    /**
     * return status.
     */
    public function isUnPaid()
    {
        return $this->status === InvoiceStatus::UNPAID;
    }
}
