<?php

namespace ClientBundle\Controller;

use ClientBundle\Traits\HasClientControllerUtils;
use CondoBundle\Entity\Service;
use CondoBundle\Entity\Unit;
use GenericOrderingServiceBundle\Entity\ShopItem;
use GenericOrderingServiceBundle\Entity\ShoppingCartItem;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @Route("/units/{unit}/services")
 */
class ServiceController extends Controller
{
    use HasClientControllerUtils;

    /**
     * @Route("/", name="client_service_list")
     *
     * @Template("ClientBundle:Service:index.html.twig")
     *
     * @param Unit $unit
     *
     * @return array
     */
    public function indexAction(
        Unit $unit
    ) {
        $this->assertCanAccessUnit($unit);

        $services = $this->getServiceRepository()
            ->findAvailableServicesForCondominium($unit->getCondominium())
            ->getQuery()
            ->getResult();

        return $this->getResponseParameters(
            [
                'unit' => $unit,
                'services' => $services,
            ]
        );
    }

    /**
     * @Route("/{service}", name="client_service_show")
     *
     * @Template("ClientBundle:Service:show.html.twig")
     *
     * @param Unit    $unit
     * @param Service $service
     *
     * @return array
     */
    public function showAction(
        Unit $unit,
        Service $service
    ) {
        $this->assertCanAccessUnit($unit, $service);

        return $this->getResponseParameters(
            [
                'unit' => $unit,
                'service' => $service,
                'shoppingCart' => $this->getShoppingCart($service),
            ]
        );
    }

    /**
     * @Route("/{service}/cart/{shopItem}")
     * @Method("POST")
     *
     * @param Unit     $unit
     * @param Service  $service
     * @param ShopItem $shopItem
     *
     * @return RedirectResponse
     */
    public function addCartItem(
        Unit $unit,
        Service $service,
        ShopItem $shopItem
    ) {
        $this->assertCanAccessUnit($unit, $service);

        $shoppingCart = $this->getShoppingCart($service);

        $vat = 0;
        if ($service->getServiceProvider()->isVat()) {
            $vat = $service->getServiceProvider()->getRate();
        }

        $cartItem = null;

        foreach ($shoppingCart->getCartItems() as $item) {
            if ($item->getShopItem() == $shopItem) {
                $cartItem = $item;
                break;
            }
        }

        if (empty($cartItem)) {
            $cartItem = new ShoppingCartItem();
            $cartItem->setShopItem($shopItem);
            if ($service->getServiceProvider()->getCurrency() !== null) {
                $cartItem->setCurrency($service->getServiceProvider()->getCurrency());
            }
            $subTotal = $shopItem->getPrice();
            $grandTotal = $subTotal * (1 + $vat / 100);
            $cartItem->setVat($vat);
            $cartItem->setSubTotal($subTotal);
            $cartItem->setGrandTotal($grandTotal);
            $this->persistAndFlush($cartItem);

            $shoppingCart->addCartItem($cartItem);
            $this->persistAndFlush($shoppingCart);
        } else {
            $cartItem->setQuantity(
                $cartItem->getQuantity() + 1
            );
            $this->persistAndFlush($cartItem);
        }

        return $this->redirectToRoute(
            'client_service_show',
            [
                'unit' => $unit->getId(),
                'service' => $service->getId(),
            ]
        );
    }

    /**
     * @Route("/{service}/cart/{cartItem}", name="client_service_cart_remove")
     * @Method("DELETE")
     *
     * @param Unit             $unit
     * @param Service          $service
     * @param ShoppingCartItem $cartItem
     *
     * @return RedirectResponse
     */
    public function removeCartItem(
        Unit $unit,
        Service $service,
        ShoppingCartItem $cartItem
    ) {
        $this->assertCanAccessUnit($unit, $service);

        $shoppingCart = $this->getShoppingCart($service);

        $shoppingCart->removeCartItem($cartItem);
        $this->persistAndFlush($shoppingCart);
        $this->removeAndFlush($cartItem);

        return $this->redirectToRoute(
            'client_service_show',
            [
                'unit' => $unit->getId(),
                'service' => $service->getId(),
            ]
        );
    }
}
