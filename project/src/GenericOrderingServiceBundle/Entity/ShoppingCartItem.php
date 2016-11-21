<?php

namespace GenericOrderingServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CondoBundle\Traits\HasEntityCreationDate;
use CondoBundle\Traits\HasEntityId;
use CondoBundle\Traits\HasEntityCurrency;
use CondoBundle\Traits\IsVatEntity;
use CondoBundle\Traits\HasEntitySubTotal;
use CondoBundle\Traits\HasEntityGrandTotal;
use DateTime;

/**
 * Represents a user's shopping cart item with its quantity.
 *
 * @ORM\Table(name="shopping_cart_item")
 * @ORM\Entity(
 *     repositoryClass="GenericOrderingServiceBundle\Repository\ShoppingCartItemRepository"
 * )
 */
class ShoppingCartItem
{
    use HasEntityId;
    use HasEntityCurrency;
    use IsVatEntity;
    use HasEntitySubTotal;
    use HasEntityGrandTotal;
    use HasEntityCreationDate;

    public function __construct()
    {
        $this->creationDate = new DateTime();
        $this->quantity = 1;
    }

    /**
     * @var ShopItem
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *     targetEntity="GenericOrderingServiceBundle\Entity\ShopItem",
     *     fetch="EAGER"
     * )
     */
    private $shopItem;

    /**
     * @var int
     * @ORM\Column(type="smallint", nullable=false)
     */
    private $quantity;

    //region Accessors

    /**
     * @return ShopItem
     */
    public function getShopItem()
    {
        return $this->shopItem;
    }

    /**
     * @param ShopItem $shopItem
     *
     * @return ShoppingCartItem
     */
    public function setShopItem(ShopItem $shopItem)
    {
        $this->shopItem = $shopItem;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     *
     * @return ShoppingCartItem
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    //endregion
}
