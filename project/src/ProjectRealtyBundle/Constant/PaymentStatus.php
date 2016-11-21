<?php

namespace ProjectRealtyBundle\Constant;

class PaymentStatus
{
    const DRAFT = 1;
    const INVOICED = 2;
    const PAID = 3;
    const CANCELLED = 4;

    private static $statuses = [
        self::DRAFT => 'payment.status.draft',
        self::INVOICED => 'payment.status.invoiced',
        self::PAID => 'payment.status.paid',
        self::CANCELLED => 'payment.status.cancelled',
    ];

    /**
     * Gets an associative array of status_id => status_label.
     *
     * @return array
     */
    public static function getStatuses()
    {
        return self::$statuses;
    }

    /**
     * Gets the label for a specific payment status.
     *
     * @param int $status
     *
     * @return string
     */
    public static function getStatusLabel($status)
    {
        if (isset(self::$statuses[$status])) {
            return self::$statuses[$status];
        }

        return 'N/A';
    }
}
