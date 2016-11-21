<?php

namespace GenericOrderingServiceBundle\Entity;

use CondoBundle\Entity\Service;
use CondoBundle\Entity\Unit;
use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityId;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use WeBridge\UserBundle\Entity\User;

/**
 * GenericOrder.
 *
 * @ORM\Table(name="generic_order")
 * @ORM\Entity(repositoryClass="GenericOrderingServiceBundle\Repository\GenericOrderRepository")
 */
class GenericOrder
{
    use HasEntityId;
    use HasEntityCreationDate;

    public function __construct()
    {
        $this->creationDate = new DateTime();
        $this->status = 0;
    }

    /**
     * The shopping cart ordered.
     *
     * @var ShoppingCart
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\OneToOne(
     *     targetEntity="GenericOrderingServiceBundle\Entity\ShoppingCart",
     *     inversedBy="order",
     *     fetch="EAGER",
     *     cascade={"ALL"}
     * )
     */
    private $shoppingCart;

    /**
     * The order status as an integer as listed in the OrderStatus class constants.
     *
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $status;

    /**
     * The expected delivery time.
     *
     * @var DateTime
     *
     * @ORM\Column(type="datetimetz", nullable=false)
     */
    private $expectedDeliveryTime;

    /**
     * The service handling the order.
     *
     * @var Service
     *
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\Service"
     * )
     */
    private $service;

    /**
     * The client's unit for delivery.
     *
     * @var Unit
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\Unit"
     * )
     */
    private $unit;

    /**
     * The order client.
     *
     * @var User
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *     targetEntity="WeBridge\UserBundle\Entity\User"
     * )
     */
    private $client;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $comments;

    public function getTotal()
    {
        $total = 0;
        foreach ($this->getShoppingCart()->getCartItems() as $cartIem) {
            $total += $cartIem->getQuantity() * $cartIem->getSubTotal();
        }

        return $total;
    }

    public function getGrandTotal()
    {
        $grandTotal = 0;
        foreach ($this->getShoppingCart()->getCartItems() as $cartIem) {
            $grandTotal += $cartIem->getQuantity() * $cartIem->getGrandTotal();
        }

        return $grandTotal;
    }

    // region Accessors

    /**
     * @return ShoppingCart
     */
    public function getShoppingCart()
    {
        return $this->shoppingCart;
    }

    /**
     * @param ShoppingCart $shoppingCart
     *
     * @return GenericOrder
     */
    public function setShoppingCart(ShoppingCart $shoppingCart)
    {
        $this->shoppingCart = $shoppingCart;
        $this->shoppingCart->setLocked(true);

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return GenericOrder
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getExpectedDeliveryTime()
    {
        return $this->expectedDeliveryTime;
    }

    /**
     * @param DateTime $expectedDeliveryTime
     *
     * @return GenericOrder
     */
    public function setExpectedDeliveryTime($expectedDeliveryTime)
    {
        $this->expectedDeliveryTime = $expectedDeliveryTime;

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
     * @return GenericOrder
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return Unit
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param Unit $unit
     *
     * @return GenericOrder
     */
    public function setUnit(Unit $unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return User
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param User $client
     *
     * @return GenericOrder
     */
    public function setClient(User $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param string $comments
     *
     * @return GenericOrder
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    // endregion
}
