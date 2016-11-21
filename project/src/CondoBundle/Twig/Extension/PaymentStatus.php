<?php

namespace CondoBundle\Twig\Extension;

use ProjectRealtyBundle\Constant\PaymentStatus as Statuses;

class PaymentStatus extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter(
                'paymentStatus',
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
        return 'payment_status';
    }
}
