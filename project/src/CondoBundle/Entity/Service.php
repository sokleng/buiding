<?php

namespace CondoBundle\Entity;

use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityId;
use CondoBundle\Traits\HasEntityManagers;
use CondoBundle\Traits\HasEntityExchangeSetting;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use GenericOrderingServiceBundle\Entity\ShopItem;

/**
 * Service.
 *
 * @ORM\Table(name="service")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\ServiceRepository")
 */
class Service
{
    use HasEntityId;
    use HasEntityCreationDate;
    use HasEntityManagers;
    use HasEntityExchangeSetting;

    public function __construct()
    {
        $this->creationDate = new DateTime();
        $this->managers = new ArrayCollection();
        $this->availabilities = new ArrayCollection();
        $this->unavailabilities = new ArrayCollection();
        $this->shopItems = new ArrayCollection();
        $this->condominiums = new ArrayCollection();
    }

    /**
     * @var ServiceProvider
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\ServiceProvider"
     * )
     */
    private $serviceProvider;

    /**
     * The service type identifier (int).
     * The service types are hardcoded and available as constants under the
     * ServiceType class.
     *
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $type;

    /**
     * The service title as displayed in the service list.
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    private $title;

    /**
     * The service description as displayed in the service details.
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $description;

    /**
     * @var ServiceAvailability[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="CondoBundle\Entity\ServiceAvailability",
     *     mappedBy="service"
     * )
     */
    private $availabilities;

    /**
     * @var ServiceUnavailability[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="CondoBundle\Entity\ServiceUnavailability",
     *     mappedBy="service"
     * )
     */
    private $unavailabilities;

    /**
     * The condominiums where this service is available.
     *
     * @var Condominium[]|ArrayCollection
     *
     * @ORM\ManyToMany(
     *     targetEntity="CondoBundle\Entity\Condominium"
     * )
     */
    private $condominiums;

    /**
     * @var ShopItem[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="GenericOrderingServiceBundle\Entity\ShopItem",
     *     mappedBy="service"
     * )
     */
    private $shopItems;

    public function isOpen()
    {
        $now = new DateTime();
        $isOpen = false;

        // Checking for normal opening times.
        foreach ($this->availabilities as $availability) {
            // If we find an availability matching date we can stop searching.
            if ($availability->isDateInRange($now)) {
                $isOpen = true;
                break;
            }
        }

        // If it's not opened, no need to check additional closing times.
        if (!$isOpen) {
            return false;
        }

        // Checking for special closing times.
        foreach ($this->unavailabilities as $unavailability) {
            // If we find a closing time matching date the store is not open.
            if ($unavailability->isDateInRange($now)) {
                return false;
            }
        }

        // If we haven't returned yet then the store is open.
        return true;
    }

    /**
     * @return ShopItem[]|ArrayCollection
     */
    public function enabledShopItems()
    {
        return $this->shopItems->filter(
            function (ShopItem $shopItem) {
                return $shopItem->isEnabled();
            }
        );
    }

    //region Accessors
    /**
     * @return ServiceProvider
     */
    public function getServiceProvider()
    {
        return $this->serviceProvider;
    }

    /**
     * @param ServiceProvider $serviceProvider
     *
     * @return Service
     */
    public function setServiceProvider(ServiceProvider $serviceProvider)
    {
        $this->serviceProvider = $serviceProvider;

        return $this;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     *
     * @return Service
     */
    public function setType($type)
    {
        $this->type = $type;

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
     * @return Service
     */
    public function setTitle($title)
    {
        $this->title = $title;

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
     * @return Service
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Adds a condominium to the service if not already present.
     *
     * @param Condominium $condominium
     */
    public function addCondominium(Condominium $condominium)
    {
        if ($this->condominiums->contains($condominium)) {
            return;
        }

        $this->condominiums->add($condominium);
    }

    /**
     * Removes a condominium (if exists) from the service.
     *
     * @param Condominium $condominium
     */
    public function removeCondominium(Condominium $condominium)
    {
        $this->condominiums->removeElement($condominium);
    }

    /**
     * Gets served condominiums by current service.
     *
     * @return Condominium[]|ArrayCollection
     */
    public function getCondominiums()
    {
        return $this->condominiums;
    }

    /**
     * @return ArrayCollection|ShopItem[]
     */
    public function getShopItems()
    {
        return $this->shopItems;
    }
    //endregion
}
