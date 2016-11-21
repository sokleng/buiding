<?php

namespace CondoBundle\Entity;

use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityId;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use RealtyCompanyBundle\Entity\CompanyContact;

/**
 * @ORM\Entity(
 *     repositoryClass="CondoBundle\Repository\DatabaseFileRepository"
 * )
 */
class DatabaseFile
{
    use HasEntityId;
    use HasEntityCreationDate;

    public function __construct(File $file)
    {
        $this->creationDate = new \DateTime();
        $this->data = file_get_contents($file->getPathname());
        $this->mimeType = $file->getMimeType();
        $this->extension = $file->guessExtension();
        $this->name = $file->getClientOriginalName();
    }

    /**
     * The file content stored in a blob.
     *
     * @var resource
     *
     * @ORM\Column(type="blob")
     */
    private $data;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $mimeType;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $extension;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $description;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @var CompanyContact
     * @ORM\JoinColumn(nullable=true)
     * @ORM\ManyToOne(
     *      targetEntity="RealtyCompanyBundle\Entity\CompanyContact",
     *      inversedBy="databaseFiles"
     *  )
     */
    private $contact;

    /**
     * @var Issue
     * @ORM\JoinColumn(nullable=true)
     * @ORM\ManyToOne(
     *      targetEntity="CondoBundle\Entity\Issue",
     *      inversedBy="databaseFiles"
     *  )
     */
    private $issue;

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return resource
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @return DatabaseFile
     */
    public function setContact(CompanyContact $contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * @return CompanyContact
     */
    public function getContact()
    {
        return $this->$contact;
    }

    /**
     * @return DatabaseFile
     */
    public function setIssue(Issue $issue)
    {
        $this->issue = $issue;
    }

    /**
     * @return Issue
     */
    public function getIssue()
    {
        return $this->issue;
    }

    /**
     * @return bool
     */
    public function isVideo()
    {
        $arrVideos = [
                        'video/mp4',
                     ];
        if (in_array($this->mimeType, $arrVideos)) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isImage()
    {
        $arrImages = [
                        'image/jpeg',
                        'image/jpg',
                        'image/png',
                        'image/gif',
                     ];
        if (in_array($this->mimeType, $arrImages)) {
            return true;
        }

        return false;
    }
}
