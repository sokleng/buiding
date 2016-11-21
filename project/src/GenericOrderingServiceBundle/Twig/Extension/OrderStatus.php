<?php

namespace GenericOrderingServiceBundle\Twig\Extension;

use GenericOrderingServiceBundle\Constant\OrderStatus as Statuses;

class OrderStatus extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter(
                'orderStatus',
                function ($value) {
                    return Statuses::getStatusLabel($value);
                }
            ),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'order_status';
    }
}
