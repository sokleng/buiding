<?php

namespace CondominiumManagementBundle\Constant;

class PaymentMethod
{
    const DAY = 1;
    const MONTH = 2;

    private static $methods = [
        self::DAY => 'client.payment.method.day',
        self::MONTH => 'client.payment.method.month',
    ];

    /**
     * Gets an associative array of method_id => method_label.
     *
     * @return array
     */
    public static function getMethods()
    {
        return self::$methods;
    }

    /**
     * Gets the label for a specific payment method.
     *
     * @param int $method
     *
     * @return string
     */
    public static function getMethodsLabel($method)
    {
        if (isset(self::$methods[$method])) {
            return self::$methods[$method];
        }

        return 'N/A';
    }
}
