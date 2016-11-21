<?php

namespace CondoBundle\Twig\Extension;

use CondoBundle\Constant\InvoiceStatus as Statuses;

class InvoiceStatus extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter(
                'InvoiceStatus',
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
        return 'invoice_status';
    }
}
