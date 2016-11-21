<?php

namespace CondoBundle\Twig\Extension;

use CondominiumManagementBundle\Constant\PaymentMethod as Methods;

class PaymentMethod extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter(
                'paymentMethod',
                function ($value) {
                    return Methods::getMethodsLabel($value);
                }
            ),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'payment_method';
    }
}
