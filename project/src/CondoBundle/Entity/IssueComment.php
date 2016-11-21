<?php

namespace CondoBundle\Entity;

use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityId;
use CondoBundle\Traits\HasEntityUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a comment from a user in an issue. This is both from support and client.
 * It allows a resident and a manager to discuss the issue further.
 *
 * @ORM\Table(name="issue_comment")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\IssueCommentRepository")
 */
class IssueComment
{
    use HasEntityId;
    use HasEntityUser;
    use HasEntityCreationDate;

    public function __construct()
    {
        $this->creationDate = new \DateTime();
        $this->readByManagement = false;
        $this->readByResident = false;
    }

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    private $comment;

    /**
     * @var Issue
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\Issue",
     *     inversedBy="comments"
     * )
     */
    private $issue;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $readByResident;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $readByManagement;

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     *
     * @return IssueComment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Issue
     */
    public function getIssue()
    {
        return $this->issue;
    }

    /**
     * @param Issue $issue
     *
     * @return IssueComment
     */
    public function setIssue(Issue $issue)
    {
        $this->issue = $issue;

        return $this;
    }

    /**
     * @return bool
     */
    public function isReadByResident()
    {
        return $this->readByResident;
    }

    /**
     * @param bool $readByResident
     *
     * @return IssueComment
     */
    public function setReadByResident($readByResident)
    {
        $this->readByResident = $readByResident;

        return $this;
    }

    /**
     * @return bool
     */
    public function isReadByManagement()
    {
        return $this->readByManagement;
    }

    /**
     * @param bool $readByManagement
     *
     * @return IssueComment
     */
    public function setReadByManagement($readByManagement)
    {
        $this->readByManagement = $readByManagement;

        return $this;
    }
}
