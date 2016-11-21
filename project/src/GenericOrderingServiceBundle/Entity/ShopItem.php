<?php

namespace GenericOrderingServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CondoBundle\Entity\DatabaseFile;
use CondoBundle\Entity\Service;
use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityDescription;
use CondoBundle\Traits\HasEntityEnabled;
use CondoBundle\Traits\HasEntityId;
use CondoBundle\Traits\HasEntityName;
use CondoBundle\Traits\HasEntityPrice;
use DateTime;

/**
 * Represents a Shop item to be sold.
 *
 * @ORM\Table(name="shop_item")
 * @ORM\Entity(repositoryClass="GenericOrderingServiceBundle\Repository\ShopItemRepository")
 */
class ShopItem
{
    use HasEntityId;
    use HasEntityCreationDate;
    use HasEntityEnabled;
    use HasEntityName;
    use HasEntityDescription;
    use HasEntityPrice;

    public function __construct()
    {
        $this->creationDate = new DateTime();
        $this->enabled = false;
    }

    /**
     * The service selling that shop item.
     *
     * @var Service
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\Service",
     *     inversedBy="shopItems"
     * )
     */
    private $service;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $reference;

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

    //region Accessors

    /**
     * @return Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param Service $service
     *
     * @return ShopItem
     */
    public function setService(Service $service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     *
     * @return ShopItem
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

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
     * @return ShopItem
     */
    public function setPicture(DatabaseFile $picture)
    {
        $this->picture = $picture;

        return $this;
    }

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
     * @return ShopItem
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    //endregion
}
