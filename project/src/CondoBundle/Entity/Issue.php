<?php

namespace CondoBundle\Entity;

use CondoBundle\Constant\IssueStatus;
use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityDescription;
use CondoBundle\Traits\HasEntityId;
use CondoBundle\Traits\HasEntityUser;
use CondoBundle\Traits\HasEntityCurrency;
use CondoBundle\Traits\HasEntityExchangeSetting;
use CondoBundle\Traits\IsVatEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Issue.
 *
 * @ORM\Table(name="issue")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\IssueRepository")
 */
class Issue
{
    use HasEntityId;
    use HasEntityCreationDate;
    use HasEntityUser;
    use HasEntityDescription;
    use HasEntityCurrency;
    use HasEntityExchangeSetting;
    use IsVatEntity;

    public function __construct()
    {
        $this->creationDate = new \DateTime();
        $this->status = IssueStatus::OPEN;
        $this->comments = new ArrayCollection();
    }

    /**
     * @var Unit
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\Unit",
     *     fetch="EAGER"
     * )
     */
    private $unit;

    /**
     * @var int
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $status;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $closingDate;

    /**
     * @var IssueComment[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="CondoBundle\Entity\IssueComment",
     *     mappedBy="issue"
     * )
     */
    private $comments;

    /**
     * @var DatabaseFile[]|ArrayCollection
     * @ORM\JoinColumn(nullable=true)
     * @ORM\OneToMany(
     *      targetEntity="CondoBundle\Entity\DatabaseFile",
     *      mappedBy="issue"
     *  )
     */
    private $databaseFiles;

    /**
     * @var Expend
     *
     * @ORM\OneToOne(
     *     targetEntity="CondoBundle\Entity\Expend",
     *     mappedBy="issue"
     * )
     */
    private $expend;

    /**
     * @var int
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $supplierType;

    /**
     * @var Supplier
     *
     * @ORM\JoinColumn(nullable=true)
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\Supplier",
     *     fetch="EAGER"
     * )
     */
    private $supplier;

    /**
     * Gets if the issue already add to invoice.
     *
     * @return bool
     */
    public function isInvoiced()
    {
        return $this->expend !== null;
    }

    /**
     * Gets if the issue already add to invoice.
     *
     * @return bool
     */
    public function isIssueInvoice()
    {
        return $this->expend == true;
    }

    /**
     * Gets if the issue should be considered as open or not.
     *
     * @return bool
     */
    public function isOpen()
    {
        return IssueStatus::isOpen($this->status);
    }

    /**
     * Gets if the issue status is `New`/`Open`.
     *
     * @return bool
     */
    public function isNew()
    {
        return $this->status == IssueStatus::OPEN;
    }

    /**
     * Gets if the issue status is `In Progress`.
     *
     * @return bool
     */
    public function isInProgress()
    {
        return $this->status == IssueStatus::IN_PROGRESS;
    }

    /**
     * Gets if the issue status is `Closed`.
     *
     * @return bool
     */
    public function isClosed()
    {
        return $this->status == IssueStatus::CLOSED;
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
     * @return Issue
     */
    public function setUnit(Unit $unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     *
     * @return Price
     */
    public function setPrice($price)
    {
        $this->price = $price;

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
     * @return Issue
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getClosingDate()
    {
        return $this->closingDate;
    }

    /**
     * @param \DateTime $closingDate
     *
     * @return Issue
     */
    public function setClosingDate(\DateTime $closingDate)
    {
        $this->closingDate = $closingDate;

        return $this;
    }

    /**
     * @return IssueComment[]|ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @return DatabaseFile[]|ArrayCollection
     */
    public function getDatabaseFiles()
    {
        return $this->databaseFiles;
    }

    /**
     * @return int
     */
    public function getSupplierType()
    {
        return $this->supplierType;
    }

    /**
     * @param int $supplierType
     *
     * @return Issue
     */
    public function setSupplierType($supplierType)
    {
        $this->supplierType = $supplierType;

        return $this;
    }

    /**
     * @return Supplier
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * @param Supplier $supplier
     *
     * @return Issue
     */
    public function setSupplier(Supplier $supplier)
    {
        $this->supplier = $supplier;

        return $this;
    }
}
