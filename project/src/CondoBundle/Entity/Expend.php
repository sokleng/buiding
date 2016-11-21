<?php

namespace CondoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Expend.
 *
 * @ORM\Table(name="expend")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\ExpendInvoiceRepository")
 */
class Expend extends Invoice
{
    /**
     * @var expendCategory
     *
     * @ORM\JoinColumn(nullable=true)
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\ExpendCategory"
     * )
     */
    private $expendCategory;

    /**
     * @var issue
     *
     * @ORM\JoinColumn(nullable=true)
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\Issue",
     *     inversedBy="expend",
     * )
     */
    private $issue;

    // region Accessors

    /**
     * @return expendCategory
     */
    public function getExpendCategory()
    {
        return $this->expendCategory;
    }

    /**
     * @param ExpendCategory $expendCategory
     *
     * @return Expend
     */
    public function setExpendCategory(ExpendCategory $expendCategory)
    {
        $this->expendCategory = $expendCategory;

        return $this;
    }

    /**
     * @return issue
     */
    public function getIssue()
    {
        return $this->issue;
    }

    /**
     * @param Issue $issue
     *
     * @return Expend
     */
    public function setIssue(Issue $issue)
    {
        $this->issue = $issue;

        return $this;
    }

    // endregion
}
