<?php

namespace CondoBundle\Entity;

use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityId;
use CondoBundle\Traits\HasEntityName;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Country.
 *
 * @ORM\Table(name="country")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\CountryRepository")
 */
class Country
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
     * @ORM\Column(type="string")
     */
    private $code;

    //region Accessors

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return Country
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    //endregion
}
