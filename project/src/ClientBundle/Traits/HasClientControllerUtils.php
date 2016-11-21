<?php

namespace ClientBundle\Traits;

use CondoBundle\Entity\Service;
use CondoBundle\Entity\Unit;
use CondoBundle\Traits\HasControllerUtils;
use GenericOrderingServiceBundle\Entity\ShoppingCart;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

trait HasClientControllerUtils
{
    use HasControllerUtils;

    /**
     * Gets the data used to display the left menu in the client space.
     * It contains all condominium and units associated with the current
     * user.
     *
     * @return array
     */
    protected function getUserCondominiums()
    {
        $user = $this->getUser();

        return $this->getCondominiumRepository()
            ->findUserCondominiums($user)
            ->getQuery()
            ->getResult();
    }

    protected function getResponseParameters($params = [])
    {
        return array_merge(
            $params,
            [
                'menuData' => $this->getResidentRepository()
                    ->getClientMenuData($this->getUser()),
            ]
        );
    }

    /**
     * Gets the shopping cart of the current user for a given service.
     *
     * @param Service $service
     *
     * @return ShoppingCart
     */
    protected function getShoppingCart(Service $service)
    {
        // Finding existing shopping cart for the current user/service.
        $shoppingCart = $this->getShoppingCartRepository()
            ->findUserShoppingCart(
                $this->getUser(),
                $service
            );

        // Shopping cart does not exist yet for that service, creating it.
        if (empty($shoppingCart)) {
            $shoppingCart = new ShoppingCart();
            $shoppingCart->setUser($this->getUser());
            $shoppingCart->setService($service);

            $this->persistAndFlush($shoppingCart);
        }

        return $shoppingCart;
    }

    /**
     * Asserts a given shopping cart is valid for a new order.
     *
     * @param ShoppingCart $shoppingCart
     */
    protected function assertValidShoppingCart(ShoppingCart $shoppingCart)
    {
        // Checking the shopping cart wasn't used yet
        if (
            $shoppingCart->isLocked() ||
            $shoppingCart->getOrder() != null ||
            $shoppingCart->getCartItems()->isEmpty()
        ) {
            throw new \LogicException('Invalid order');
        }

        // Checking cart items are enabled
        foreach ($shoppingCart->getCartItems() as $item) {
            if (
                !$item->getShopItem()->isEnabled()
            ) {
                throw new \LogicException('Invalid cart item');
            }
        }
    }

    /**
     * Asserts current user can access a given unit.
     *
     * @param Unit       $unit
     * @param mixed|null $object
     *
     * @throws AccessDeniedException
     * @throws BadRequestHttpException
     */
    protected function assertCanAccessUnit(
        Unit $unit,
        $object = null
    ) {
        $user = $this->getUser();

        $found = false;
        foreach ($unit->getResidents() as $resident) {
            if ($resident->getUser() == $user) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            throw new AccessDeniedException(
                'User is not a resident of specified unit.'
            );
        }

        // Given object does not belong to the given unit.
        if (
            !empty($object) &&
            method_exists($object, 'getUnit') &&
            $object->getUnit() != $unit
        ) {
            throw new BadRequestHttpException(
                'Given object does not belong to the given Unit.'
            );
        }

        if (
            $object instanceof Service &&
            !$object->getCondominiums()->contains($unit->getCondominium())
        ) {
            throw new BadRequestHttpException(
                'Given Service does not belong to the given Unit.'
            );
        }
    }
}
