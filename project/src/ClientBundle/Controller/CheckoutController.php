<?php

namespace ClientBundle\Controller;

use ClientBundle\Traits\HasClientControllerUtils;
use CondoBundle\Entity\Service;
use CondoBundle\Entity\Unit;
use GenericOrderingServiceBundle\Constant\OrderStatus;
use GenericOrderingServiceBundle\Entity\GenericOrder;
use GenericOrderingServiceBundle\Entity\ShoppingCart;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use CondoBundle\Entity\Condominium;
use WeBridge\UserBundle\Entity\User;

/**
 * @Route("/units/{unit}/service/{service}/checkout")
 */
class CheckoutController extends Controller
{
    use HasClientControllerUtils;

    /**
     * @Route(
     *     path="/{shoppingCart}",
     *     name="client_service_checkout"
     * )
     * @Method("GET")
     * @Template("ClientBundle:Checkout:checkoutOverview.html.twig")
     *
     * @param Service      $service
     * @param Unit         $unit
     * @param ShoppingCart $shoppingCart
     *
     * @return array
     */
    public function checkoutOverviewAction(
        Unit $unit,
        Service $service,
        ShoppingCart $shoppingCart
    ) {
        $this->assertCanAccessUnit($unit, $service);
        $this->assertValidShoppingCart($shoppingCart);

        return $this->getResponseParameters(
            [
                'unit' => $unit,
                'service' => $service,
                'shoppingCart' => $shoppingCart,
            ]
        );
    }

    /**
     * Creates and order from a shopping cart.
     *
     * @Route(path="/{shoppingCart}", name="client_service_checkout_submit")
     * @Method("POST")
     *
     * @param Request      $request
     * @param Unit         $unit
     * @param Service      $service
     * @param ShoppingCart $shoppingCart
     *
     * @return RedirectResponse
     */
    public function submitOrderAction(
        Request $request,
        Unit $unit,
        Service $service,
        ShoppingCart $shoppingCart
    ) {
        $this->assertCanAccessUnit($unit, $service);
        $this->assertValidShoppingCart($shoppingCart);

        $additionalInfo = $request->get('additional_info');

        $order = new GenericOrder();
        $order->setShoppingCart($shoppingCart)
            ->setService($service)
            ->setClient($this->getUser())
            ->setUnit($unit)
            ->setStatus(OrderStatus::SUBMITTED)
            ->setExpectedDeliveryTime(new \DateTime())
            ->setComments($additionalInfo);

        $this->persistAndFlush($order);

        return $this->redirectToRoute(
            'client_orders_list',
            [
                'unit' => $unit->getId(),
            ]
        );
    }

    /**
     * Gets user who checkout product.
     *
     * @Template("ClientBundle:Checkout:client.html.twig")
     *
     * @param Condominium $condominium
     * @param User        $user
     *
     * @return array
     */
    public function getUserPhoneAction(
        Condominium $condominium,
        User $user
    ) {
        $checkoutUser = $this->getClientUnitRepository()
            ->findUserByCondoAndUser($condominium, $user);

        return $this->getResponseParameters(
            [
                'checkoutUser' => $checkoutUser,
            ]
        );
    }
}
