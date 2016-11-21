<?php

namespace CondoBundle\Twig\Extension;

use CondominiumManagementBundle\Constant\ClientStatus as Statuses;

class ClientStatus extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter(
                'clientStatus',
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
        return 'client_status';
    }
}
