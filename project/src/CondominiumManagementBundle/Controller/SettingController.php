<?php

namespace CondominiumManagementBundle\Controller;

use CondoBundle\Entity\Condominium;
use CondominiumManagementBundle\Traits\HasCondominiumManagementUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CondoBundle\Entity\ExchangeSetting;

/**
 * @Route("/{condominium}/setting")
 */
class SettingController extends Controller
{
    use HasCondominiumManagementUtils;

    /**
     * Exchange Setting.
     *
     * @Route("/exchange", name="condominium_setting_exchange")
     * @Method({"GET","POST"})
     * @Template("CondominiumManagementBundle:Setting:exchange.html.twig")
     *
     * @param Request     $request
     * @param Condominium $condominium
     *
     * @return array
     */
    public function exchangeAction(
        Request $request,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium);

        if ($request->isMethod('POST')) {
            $exchange = $request->get('exchange');
            $currency = $request->get('currency');
            $value = array_combine($currency, $exchange);
            $exchangeSetting = new ExchangeSetting();
            $exchangeSetting->setValue($value);
            $this->persistAndFlush($exchangeSetting);

            $condominium->setExchangeSetting($exchangeSetting);
            $this->persistAndFlush($condominium);
        }

        $currencies = $this->getCurrencyRepository()
            ->findAllCurrencies()
            ->getQuery()
            ->getResult();

        return $this->getResponseParameters(
            [
                'condominium' => $condominium,
                'currencies' => $currencies,
            ]
        );
    }
}
