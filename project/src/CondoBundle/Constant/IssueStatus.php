<?php

namespace CondoBundle\Constant;

class IssueStatus
{
    /**
     * Opened issue, the default status when newly created.
     */
    const OPEN_AND_IN_PROGRESS = 5;

    /**
     * Opened issue, the default status when newly created.
     */
    const OPEN = 1;

    /**
     * Issue in progress and should be closed soon (TM).
     */
    const IN_PROGRESS = 2;

    /**
     * Issue has been resolved.
     */
    const CLOSED = 3;

    /**
     * Issue has been canceled by the author.
     */
    const CANCELLED = 4;

    private static $statuses = [
        self::OPEN => 'New',
        self::IN_PROGRESS => 'In Progress',
        self::CLOSED => 'Closed',
        self::CANCELLED => 'Cancelled',
    ];

    /**
     * Gets an associative array of issue_status_id => issue_status_label.
     *
     * @return array
     */
    public static function getStatuses()
    {
        return self::$statuses;
    }

    /**
     * Gets the label for a specific issue status.
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

    /**
     * Gets an array with all the status ids representing an open issue.
     */
    public static function getOpenStatuses()
    {
        return [
            self::OPEN,
            self::IN_PROGRESS,
        ];
    }

    /**
     * Gets if a status is an open one.
     *
     * @param int $status
     *
     * @return bool
     */
    public static function isOpen($status)
    {
        return in_array($status, self::getOpenStatuses());
    }
}
