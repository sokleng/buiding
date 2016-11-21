<?php

namespace CondoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CondoBundle\Traits\HasEntityId;

/**
 * Exchange Setting.
 *
 * @ORM\Table(name="exchange_setting")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\ExchangeSettingRepository")
 */
class ExchangeSetting
{
    use HasEntityId;

    /**
     * @var json_array
     *
     * @ORM\Column(type="json_array", nullable=false)
     */
    private $value;

    /**
     * @return json
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param $value
     *
     * @return ExchangeSetting
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}
