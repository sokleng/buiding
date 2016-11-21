<?php

namespace DeveloperBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use DeveloperBundle\Entity\Developer;
use DeveloperBundle\Traits\HasDeveloperControllerUtils;

/**
 * @Route("/{developer}/payments")
 */
class DeveloperPaymentController extends Controller
{
    use HasDeveloperControllerUtils;
    /**
     * @Route("/", name="developer_payments_list")
     * @Method("GET")
     * @Template("DeveloperBundle:DeveloperPayment:list.html.twig")
     *
     * @param Developer $developer
     *
     * @return array
     */
    public function listAction(Developer $developer)
    {
        $payments = $this->getDeveloperPaymentRepository()
            ->findAllByDeveloper($developer)
            ->getQuery()
            ->getResult()
        ;

        return $this->getResponseParameters([
            'payments' => $payments,
        ]);
    }
}
