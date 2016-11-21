<?php

namespace CondoBundle\Constant;

class InvoiceStatus
{
    /**
     * Unpaid Invoice, the default status when newly created.
     */
    const UNPAID = 1;

    /**
     * The invoice paid.
     */
    const PAID = 2;

    /**
     * All status paid and unpaid.
     */
    const ALL = 3;

    private static $statuses = [
        self::ALL => 'condo.invoice.all',
        self::UNPAID => 'condo.invoice.unpaid',
        self::PAID => 'condo.invoice.paid',
    ];

    /**
     * Gets an associative array of invoice_status_id => invoice_status_label.
     *
     * @return array
     */
    public static function getStatuses()
    {
        return self::$statuses;
    }

    /**
     * Gets the label for a specific invoice status.
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
