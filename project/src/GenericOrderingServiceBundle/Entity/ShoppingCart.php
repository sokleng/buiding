<?php

namespace GenericOrderingServiceBundle\Entity;

use CondoBundle\Entity\Service;
use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityId;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use WeBridge\UserBundle\Entity\User;

/**
 * Represents a user's shopping cart.
 *
 * @ORM\Table(name="shopping_cart")
 * @ORM\Entity(
 *     repositoryClass="GenericOrderingServiceBundle\Repository\ShoppingCartRepository"
 * )
 */
class ShoppingCart
{
    use HasEntityId;
    use HasEntityCreationDate;

    public function __construct()
    {
        $this->creationDate = new DateTime();
        $this->cartItems = new ArrayCollection();
        $this->isLocked = false;
    }

    /**
     * @var ShoppingCartItem[]|ArrayCollection
     *
     * @ORM\ManyToMany(
     *     targetEntity="GenericOrderingServiceBundle\Entity\ShoppingCartItem",
     *     fetch="EAGER"
     * )
     */
    private $cartItems;

    /**
     * @var User
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *     targetEntity="WeBridge\UserBundle\Entity\User",
     *     fetch="EAGER"
     * )
     */
    private $user;

    /**
     * @var Service
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\Service",
     *     fetch="EAGER"
     * )
     */
    private $service;

    /**
     * @var GenericOrder
     *
     * @ORM\OneToOne(
     *     targetEntity="GenericOrderingServiceBundle\Entity\GenericOrder",
     *     mappedBy="shoppingCart",
     *     fetch="EAGER"
     * )
     */
    private $order;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isLocked;

    //region Accessors

    /**
     * Gets the shopping cart items.
     *
     * @return ArrayCollection|ShoppingCartItem[]
     */
    public function getCartItems()
    {
        return $this->cartItems;
    }

    /**
     * Adds an item to the shopping cart.
     *
     * @param ShoppingCartItem $shopItem
     *
     * @return ShoppingCart
     */
    public function addCartItem(ShoppingCartItem $shopItem)
    {
        if ($this->isLocked()) {
            throw new \LogicException('Trying to add item of a locked shopping cart');
        }

        $this->cartItems->add($shopItem);

        return $this;
    }

    /**
     * Removes and item from the shopping cart.
     *
     * @param ShoppingCartItem $shopItem
     *
     * @return ShoppingCart
     */
    public function removeCartItem(ShoppingCartItem $shopItem)
    {
        if ($this->isLocked()) {
            throw new \LogicException('Trying to remove item of a locked shopping cart');
        }

        $this->cartItems->removeElement($shopItem);

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return ShoppingCart
     */
    public function setUser(User $user)
    {
        if ($this->isLocked()) {
            throw new \LogicException('Trying to change user of a locked shopping cart');
        }
        $this->user = $user;

        return $this;
    }

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
     * @return ShoppingCart
     */
    public function setService(Service $service)
    {
        if ($this->isLocked()) {
            throw new \LogicException('Trying to change service of a locked shopping cart');
        }

        $this->service = $service;

        return $this;
    }

    /**
     * Get order.
     *
     * @return GenericOrder
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return bool
     */
    public function isLocked()
    {
        return $this->isLocked;
    }

    /**
     * @param bool $isLocked
     *
     * @return ShoppingCart
     */
    public function setLocked($isLocked)
    {
        $this->isLocked = $isLocked;

        return $this;
    }

    //endregion
}
