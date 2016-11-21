<?php

namespace ProjectRealtyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectPayment.
 *
 * @ORM\Table(name="project_payment")
 * @ORM\Entity(repositoryClass="ProjectRealtyBundle\Repository\ProjectPaymentRepository")
 */
class ProjectPayment extends Payment
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @var ProjectUnit
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *     targetEntity="ProjectRealtyBundle\Entity\ProjectUnit",
     *     inversedBy="payments"
     * )
     */
    private $unit;

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
     * @return Payment
     */
    public function setUnit(ProjectUnit $unit)
    {
        $this->unit = $unit;

        return $this;
    }
}
