<?php

namespace DeveloperBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ProjectRealtyBundle\Entity\Payment;
use RealtyCompanyBundle\Entity\RealtyCompany;
use ProjectRealtyBundle\Constant\PaymentStatus;

/**
 * DeveloperPayment.
 *
 * @ORM\Table(name="developer_payment")
 * @ORM\Entity(repositoryClass="DeveloperBundle\Repository\DeveloperPaymentRepository")
 */
class DeveloperPayment extends Payment
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @var Developer
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *     targetEntity="DeveloperBundle\Entity\Developer"
     * )
     */
    private $developer;

    /**
     * @var RealtyCompany
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *     targetEntity="RealtyCompanyBundle\Entity\RealtyCompany",
     *     inversedBy="developerPayments"
     * )
     */
    private $realtyCompany;

    /**
     * @return Developer
     */
    public function getDeveloper()
    {
        return $this->developer;
    }

    /**
     * @param Developer $developer
     *
     * @return Payment
     */
    public function setDeveloper(Developer $developer)
    {
        $this->developer = $developer;

        return $this;
    }

    /**
     * @return RealtyCompany
     */
    public function getRealtyCompany()
    {
        return $this->realtyCompany;
    }

    /**
     * @param RealtyCompany $realtyCompany
     *
     * @return Payment
     */
    public function setRealtyCompany(RealtyCompany $realtyCompany)
    {
        $this->realtyCompany = $realtyCompany;

        return $this;
    }

    /**
     * Is the status DRAFT?
     *
     * @return bool
     */
    public function isStatusDraft()
    {
        return $this->getStatus() === PaymentStatus::DRAFT;
    }
}
