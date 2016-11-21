<?php

namespace ProjectRealtyBundle\Entity;

/**
 * Content Contants value.
 */
class ConstantType
{
    /**
     * Type condo for rant.
     */
    const FOR_RANT = 0;

    /**
     * Type condo for sale.
     */
    const FOR_SALE = 1;

    private static $type = [
        self::FOR_RANT => 'project.public.listing.type.label.type.for.rant',
        self::FOR_SALE => 'project.public.listing.type.label.type.for.sale',
    ];

    /**
     * Get Type.
     *
     * @return array
     */
    public static function getType()
    {
        return self::$type;
    }
}
