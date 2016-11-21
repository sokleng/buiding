<?php

namespace CondoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CondoBundle\Traits\HasEntityId;
use CondoBundle\Traits\HasEntityName;
use CondoBundle\Traits\HasEntityPhone;
use CondoBundle\Traits\HasEntityAddress;
use CondoBundle\Traits\HasEntityCondominium;
use Doctrine\ORM\Mapping\Column;

/**
 * The entity of supplier and base of company and individual.
 *
 * @ORM\Table(name="supplier")
 * @ORM\InheritanceType(value="JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\SupplierRepository")
 * @ORM\DiscriminatorMap({
 *     "companySupplier" = "CompanySupplier",
 *     "individualSupllier" = "IndividualSupplier"
 * })
 */
abstract class Supplier
{
    use HasEntityId;
    use HasEntityName;
    use HasEntityPhone;
    use HasEntityAddress;
    use HasEntityCondominium;

    /**
     * @var string
     *
     * @Column(type="string", nullable=true)
     */
    private $email;

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }
}
