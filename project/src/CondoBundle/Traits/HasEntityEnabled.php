<?php

namespace CondoBundle\Traits;

use Doctrine\ORM\Mapping as ORM;

trait HasEntityEnabled
{
    /**
     * @var bool
     *
     * @ORM\Column(
     *     type="boolean",
     *     options={"default"=false}
     * )
     */
    private $enabled;

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     *
     * @return $this
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }
}
