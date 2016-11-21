<?php

namespace CondoBundle\Entity;

use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityId;
use CondoBundle\Traits\HasEntityName;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use CondoBundle\Constant\Gender;

/**
 * The base class for Contact.
 *
 * @ORM\InheritanceType(value="JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\Table(name="contact")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\ContactRepository")
 * @ORM\DiscriminatorMap({
 *      "company" = "RealtyCompanyBundle\Entity\CompanyContact",
 * })
 */
abstract class Contact
{
    use HasEntityId;
    use HasEntityCreationDate;
    use HasEntityName;

    public function __construct()
    {
        $this->creationDate = new DateTime();
    }

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $idNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nationality;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @Assert\Choice(
     *  callback = {"CondoBundle\Constant\Gender", "getGender"}
     * )
     */
    private $gender;

    /**
     * Set comment.
     *
     * @param string $comment
     *
     * @return Contact
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment.
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set address.
     *
     * @param string $address
     *
     * @return Contact
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address.
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set phoneNumber.
     *
     * @param string $phoneNumber
     *
     * @return Contact
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber.
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set idNumber.
     *
     * @param string $idNumber
     *
     * @return Contact
     */
    public function setIdNumber($idNumber)
    {
        $this->idNumber = $idNumber;

        return $this;
    }

    /**
     * Get idNumber.
     *
     * @return string
     */
    public function getIdNumber()
    {
        return $this->idNumber;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return Contact
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set nationality.
     *
     * @param string $nationality
     *
     * @return Contact
     */
    public function setNationality($nationality)
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * Get nationality.
     *
     * @return string
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    public function __toString()
    {
        $name = $this->name;
        if (!empty($this->email)) {
            $name = $name." ($this->email)";
        } elseif (!empty($this->phoneNumber)) {
            $name = $name." ($this->phoneNumber)";
        }

        return $name;
    }

    /**
     * Set gender.
     *
     * @param int $gender
     *
     * @return Contact
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender.
     *
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @return bool
     */
    public function isMale()
    {
        return $this->gender === Gender::MALE;
    }

    /**
     * @return bool
     */
    public function isFemale()
    {
        return $this->gender === Gender::FEMALE;
    }

    /**
     * @return bool
     */
    public function isNA()
    {
        return $this->gender === Gender::NA;
    }
}
