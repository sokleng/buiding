<?php

namespace CondoBundle\Traits;

use Doctrine\ORM\Mapping as ORM;
use CondoBundle\Entity\DatabaseFile;

trait HasEntityPicture
{
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
     * @return DatabaseFile
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param DatabaseFile $picture
     *
     * @return $this
     */
    public function setPicture(DatabaseFile $picture)
    {
        $this->picture = $picture;

        return $this;
    }
}
