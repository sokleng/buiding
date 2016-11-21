<?php

namespace CondoBundle\Entity;

use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityId;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use WeBridge\UserBundle\Entity\User;

/**
 * The base class for news.
 *
 * @ORM\Table(name="news")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\NewsRepository")
 * @ORM\InheritanceType(value="JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *     "condo" = "CondominiumNews",
 *     "service" = "ServiceNews",
 *     "platform" = "PlatformNews"
 * })
 */
abstract class News
{
    use HasEntityId;
    use HasEntityCreationDate;

    public function __construct()
    {
        $this->creationDate = new DateTime();
        $this->isPublished = false;
    }

    /**
     * The news title in English as displayed in the news feed.
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    private $title;

    /**
     * The news shortDescription (rich text) as displayed in the news for short details.
     *
     * @var string
     *
     * @ORM\Column(type="text", nullable=false)
     */
    private $shortDescription;

    /**
     * The news author.
     *
     * @var User
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *     targetEntity="WeBridge\UserBundle\Entity\User"
     * )
     */
    private $author;

    /**
     * @var DatabaseFile
     *
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\DatabaseFile",
     *     fetch="EAGER",
     *     cascade={"ALL"}
     * )
     */
    private $picture;

    /**
     * The News publishDate.
     *
     * @var datetime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publishDate;

    /**
     * The News endDate.
     *
     * @var datetime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @var bool
     *
     * @ORM\Column(
     *     type="boolean",
     *     options={"default"=false}
     * )
     */
    private $isPublished;

    /**
     * The news description (rich text) as displayed in the news for details.
     *
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @return datetime
     */
    public function getPublishDate()
    {
        return $this->publishDate;
    }

    /**
     * @param datetime $publishDate
     *
     * @return News
     */
    public function setPublishDate(DateTime $publishDate)
    {
        $this->publishDate = $publishDate;

        return $this;
    }

    /**
     * @return datetime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param datetime $endDate
     *
     * @return News
     */
    public function setEndDate(DateTime $endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return DatabaseFile
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param DatabaseFile $picture
     *
     * @return News
     */
    public function setPicture(DatabaseFile $picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->isPublished;
    }

    /**
     * @param bool $isPublished
     *
     * @return News
     */
    public function setIsPublished($isPublished)
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return News
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * @param string $shortDescription
     *
     * @return News
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return News
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param User $author
     *
     * @return News
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;

        return $this;
    }
}
