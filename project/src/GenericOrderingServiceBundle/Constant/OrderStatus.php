<?php

namespace GenericOrderingServiceBundle\Constant;

/**
 * Contains constants representing the different
 * order statuses possible.
 */
class OrderStatus
{
    /**
     * Newly created order, not submitted to the shop yet.
     */
    const CREATED = 0;

    /**
     * Order has been submitted to the shop.
     */
    const SUBMITTED = 1;

    /**
     * Order has been validated by a shop member
     * for shops that require validation.
     */
    const VALIDATED = 2;

    /**
     * Order has been paid by the client but was
     * not delivered yet.
     */
    const PAID = 3;

    /**
     * Order has been shipped and should be
     * received soon.
     */
    const SENT = 4;

    /**
     * Order has been received and paid by the client.
     */
    const COMPLETED = 5;

    /**
     * @var array
     */
    private static $statuses = [
        self::CREATED => 'Created',
        self::SUBMITTED => 'New',
        self::VALIDATED => 'Validated',
        self::PAID => 'Paid',
        self::SENT => 'Sent',
        self::COMPLETED => 'Completed',
    ];

    /**
     * Gets an associative array of order_status_id => order_status_label.
     *
     * @return array
     */
    public static function getStatuses()
    {
        return self::$statuses;
    }

    /**
     * Gets the label for a specific order status.
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
