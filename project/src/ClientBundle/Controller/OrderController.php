<?php

namespace ClientBundle\Controller;

use CondoBundle\Entity\Unit;
use CondoBundle\Traits\HasPagination;
use ClientBundle\Traits\HasClientControllerUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use GenericOrderingServiceBundle\Entity\GenericOrder;

/**
 * @Route("/units/{unit}/orders")
 */
class OrderController extends Controller
{
    use HasClientControllerUtils;
    use HasPagination;

    /**
     * Lists a client's order.
     *
     * @Route("/", name="client_orders_list")
     * @Method("GET")
     * @Template("ClientBundle:Order:list.html.twig")
     *
     * @param Request $request
     * @param Unit    $unit
     *
     * @return array
     */
    public function listAction(
        Request $request,
        Unit $unit
    ) {
        $this->assertCanAccessUnit($unit);
        $orders = $this->getGenericOrderRepository()
            ->findOrderOfUserInUnit($this->getUser(), $unit);

        $ordersPagination = $this->createPagination(
            $orders,
            $request
        );

        return $this->getResponseParameters(
            [
                'orders' => $ordersPagination,
                'unit' => $unit,
            ]
        );
    }

    /**
     * Show details on an given order for the current user.
     *
     * @Route("/{order}", name="client_order_history_show")
     * @Method("GET")
     * @Template("ClientBundle:Order:show.html.twig")
     *
     * @param Unit         $unit
     * @param GenericOrder $order
     *
     * @return array
     */
    public function showAction(
        Unit $unit,
        GenericOrder $order
    ) {
        $this->assertCanAccessUnit($unit, $order);

        return $this->getResponseParameters(
            [
                'unit' => $unit,
                'order' => $order,
            ]
        );
    }
}
