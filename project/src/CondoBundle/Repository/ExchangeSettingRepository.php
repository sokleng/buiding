<?php

namespace CondoBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ExchangeSettingRepository.
 *
 * This class was generated by the PhpStorm "Php Annotations" Plugin. Add your own custom
 * repository methods below.
 */
class ExchangeSettingRepository extends EntityRepository
{
    /**
     * Get exchange rate to dollar in condo or each invoice.
     *
     * @param $entityObject
     *
     * @return float
     */
    public function getUSRate($entityObject)
    {
        $currency = $entityObject->getCurrency();
        if ($currency === null) {
            return 1;
        }
        $exchangeSetting = $entityObject->getExchangeSetting();
        $rate = $exchangeSetting->getValue()[$currency->getId()];

        return $rate;
    }

    /**
     * Calculation sub total.
     *
     * @param float $grandTotal
     * @param float $vat
     *
     * @return float
     */
    public function getCalculateSubTotal(
        $grandTotal,
        $vat
    ) {
        return $grandTotal / (1 + ($vat / 100));
    }
}
