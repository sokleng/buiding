<?php

namespace CondominiumManagementBundle\Controller;

use CondoBundle\Entity\Condominium;
use CondoBundle\Traits\HasPagination;
use CondominiumManagementBundle\Traits\HasCondominiumManagementUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/{condominium}/services")
 */
class ServiceController extends Controller
{
    use HasCondominiumManagementUtils;
    use HasPagination;

    /**
     * @Route("/", name="condominium_services_list")
     * @Method("GET")
     * @Template("CondominiumManagementBundle:Service:list.html.twig")
     *
     * @param Request     $request
     * @param Condominium $condominium
     *
     * @return array
     */
    public function listAction(Request $request, Condominium $condominium)
    {
        $services = $this->getServiceRepository()
            ->findAvailableServicesForCondominium($condominium)
            ->getQuery()
            ->getResult()
        ;

        $servicesPagination = $this->createPagination(
            $services,
            $request
        );

        return $this->getResponseParameters(
            [
                'services' => $servicesPagination,
                'condominium' => $condominium,
            ]
        );
    }
}
