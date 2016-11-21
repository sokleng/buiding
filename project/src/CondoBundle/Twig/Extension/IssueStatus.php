<?php

namespace CondoBundle\Twig\Extension;

use CondoBundle\Constant\IssueStatus as Statuses;

class IssueStatus extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter(
                'issueStatus',
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
        return 'issue_status';
    }
}
