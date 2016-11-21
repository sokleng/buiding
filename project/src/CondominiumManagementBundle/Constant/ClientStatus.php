<?php

namespace CondominiumManagementBundle\Constant;

class ClientStatus
{
    const STAYING = 1;
    const LEAVE = 2;
    const ALL = 3;

    private static $statuses = [
        self::STAYING => 'condo.client.status.staying',
        self::LEAVE => 'condo.client.status.leave',
        self::ALL => 'condo.client.status.all',
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
     * Gets the label for a specific status.
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
