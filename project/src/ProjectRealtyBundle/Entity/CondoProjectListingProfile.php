<?php

namespace ProjectRealtyBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * CondoProjectListingProfile.
 *
 * @ORM\Table(name="condo_project_listing_profile")
 * @ORM\Entity(repositoryClass ="ProjectRealtyBundle\Repository\CondoProjectListingProfileRepository")
 */
class CondoProjectListingProfile extends CondoListingProfile
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetimetz")
     */
    private $constructionDate;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetimetz")
     */
    private $completionDate;

    /**
     * @var CondominiumProject
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\OneToOne(
     *     targetEntity="ProjectRealtyBundle\Entity\CondominiumProject",
     *     inversedBy="projectListing",
     *     fetch="EAGER"
     * )
     */
    private $project;

    /**
     * @return DateTime
     */
    public function getConstructionDate()
    {
        return $this->constructionDate;
    }

    /**
     * @param DateTime $constructionDate
     *
     * @return CondoProjectListingProfile
     */
    public function setConstructionDate(DateTime $constructionDate)
    {
        $this->constructionDate = $constructionDate;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCompletionDate()
    {
        return $this->completionDate;
    }

    /**
     * @param DateTime $completionDate
     *
     * @return CondoProjectListingProfile
     */
    public function setCompletionDate(DateTime $completionDate)
    {
        $this->completionDate = $completionDate;

        return $this;
    }

    /**
     * @return CondominiumProject
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param CondominiumProject $project
     *
     * @return CondoProjectListingProfile
     */
    public function setProject(CondominiumProject $project)
    {
        $this->project = $project;

        return $this;
    }
}
