<?php

namespace CondoBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * IndividualSupplier.
 *
 * @ORM\Table(name="individual_supplier")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\IndividualSupplierRepository")
 */
class IndividualSupplier extends Supplier
{
    /**
     * @var string
     *
     * @Column(type="string", nullable=true)
     */
    private $idCard;

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
     * @return IndividualSupplier
     */
    public function setIdCard($idCard)
    {
        $this->idCard = $idCard;

        return $this;
    }
}
