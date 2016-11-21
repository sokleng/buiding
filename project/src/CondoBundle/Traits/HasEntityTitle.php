<?php

namespace CondoBundle\Traits;

use Doctrine\ORM\Mapping as ORM;

trait HasEntityTitle
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    private $title;

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
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }
}
