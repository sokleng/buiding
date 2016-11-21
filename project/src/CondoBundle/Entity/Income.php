<?php

namespace CondoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Income.
 *
 * @ORM\Table(name="income")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\IncomeRepository")
 */
class Income extends Invoice
{
    /**
     * @var incomeCategory
     *
     * @ORM\JoinColumn(nullable=true)
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\IncomeCategory"
     * )
     */
    private $incomeCategory;

    /**
     * @var client
     *
     * @ORM\JoinColumn(nullable=true)
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\ClientUnit"
     * )
     */
    private $client;

    // region Accessors

    /**
     * @return incomeCategory
     */
    public function getIncomeCategory()
    {
        return $this->incomeCategory;
    }

    /**
     * @param IncomeCategory $incomeCategory
     *
     * @return Income
     */
    public function setIncomeCategory(IncomeCategory $incomeCategory)
    {
        $this->incomeCategory = $incomeCategory;

        return $this;
    }

    /**
     * @return client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param ClientUnit $client
     *
     * @return Income
     */
    public function setClient(ClientUnit $client)
    {
        $this->client = $client;

        return $this;
    }

    // endregion
}
