<?php

namespace CondoBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * CompanySupplier.
 *
 * @ORM\Table(name="company_supplier")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\CompanySupplierRepository")
 */
class CompanySupplier extends Supplier
{
    /**
     * @var int
     *
     * @Column(type="integer", nullable=true)
     */
    private $vatin;

    /**
     * @var int
     *
     * @Column(type="integer", nullable=true)
     */
    private $vat;

    /**
     * @var string
     *
     * @Column(type="string", nullable=true)
     */
    private $contactName;

    /**
     * @var string
     *
     * @Column(type="string", nullable=true)
     */
    private $fax;

    /**
     * @return int
     */
    public function getVatin()
    {
        return $this->vatin;
    }

    /**
     * @param int $vatin
     *
     * @return CompanySupplier
     */
    public function setVatin(int $vatin)
    {
        $this->vatin = $vatin;

        return $this;
    }

    /**
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param string $fax
     *
     * @return CompanySupplier
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * @return string
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * @param string $contactName
     *
     * @return CompanySupplier
     */
    public function setContactName($contactName)
    {
        $this->contactName = $contactName;

        return $this;
    }

    /**
     * @return int
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @param int $vat
     *
     * @return CompanySupplier
     */
    public function setVat(int $vat)
    {
        $this->vat = $vat;

        return $this;
    }
}
