<?php

namespace ServiceBundle\Controller;

use CondoBundle\Entity\Service;
use GenericOrderingServiceBundle\Constant\OrderStatus;
use GenericOrderingServiceBundle\Entity\GenericOrder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ServiceBundle\Traits\HasServiceControllerUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use CondoBundle\Traits\HasPagination;

/**
 * @Route(path="/service/{service}/orders")
 */
class OrderController extends Controller
{
    use HasServiceControllerUtils;
    use HasPagination;
    /**
     * Lists all the orders for the current service.
     *
     * @Route("/", name="service_orders_list")
     * @Template("ServiceBundle:Order:list.html.twig")
     *
     * @param Request $request
     * @param Service $service
     *
     * @return array
     */
    public function listAction(
        Request $request,
        Service $service
    ) {
        $this->assertUserCanAccessService($service);
        $orderStatus = empty($request->query->get('status')) ? OrderStatus::SUBMITTED : $request->query->get('status');
        $orders = [];
        if ($orderStatus === 'all') {
            $orders = $this->getGenericOrderRepository()
                ->findBy(
                    [
                        'service' => $service,
                        'status' => [
                            OrderStatus::SUBMITTED,
                            OrderStatus::PAID,
                            OrderStatus::SENT,
                            OrderStatus::VALIDATED,
                            OrderStatus::COMPLETED,
                        ],
                    ],
                    [
                        'status' => 'ASC',
                    ]
                );
        } else {
            $orders = $this->getGenericOrderRepository()
                ->findOrderByOrderStatus($service, $orderStatus);
        }

        $ordersPagination = $this->createPagination(
            $orders,
            $request
        );

        return [
            'service' => $service,
            'orders' => $ordersPagination,
            'services' => $this->getManagerServices(),
            'status' => $orderStatus,
        ];
    }

    /**
     * Marks a given order to completed.
     *
     * @Route(
     *     path="/{order}/status",
     *     name="service_orders_status_completed"
     * )
     * @Method("PATCH")
     *
     * @param Service      $service
     * @param GenericOrder $order
     *
     * @return RedirectResponse
     */
    public function markOrderAsDeliveredAction(
        Service $service,
        GenericOrder $order
    ) {
        $this->assertUserCanAccessService($service, $order);

        $order->setStatus(OrderStatus::COMPLETED);
        $this->persistAndFlush($order);

        return $this->redirectToRoute(
            'service_orders_list',
            ['service' => $service->getId()]
        );
    }
}
