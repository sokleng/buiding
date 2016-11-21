<?php

namespace ServiceBundle\Controller;

use CondoBundle\Entity\Condominium;
use CondoBundle\Entity\Service;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ServiceBundle\Traits\HasServiceControllerUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/condominium/{service}")
 */
class CondominiumController extends Controller
{
    use HasServiceControllerUtils;

    /**
     * Lists the condominium of the service.
     *
     * @Route("/", name="service_condominium_list")
     * @Method("GET")
     * @Template("ServiceBundle:Condominium:list.html.twig")
     *
     * @param Service $service
     *
     * @return array
     */
    public function condominiumListAction(Service $service)
    {
        $this->assertUserCanAccessService($service);

        $condominiums = $this->getCondominiumRepository()
            ->findAvailableCondominiumsForService($service)
            ->getQuery()
            ->getResult()
        ;

        return [
            'service' => $service,
            'services' => $this->getManagerServices(),
            'condominiums' => $condominiums,
        ];
    }

    /**
     * Removes a condominium from the served list of given service.
     *
     * @Route("/{condominium}", name="service_condominium_remove")
     * @Method("DELETE")
     *
     * @param Service     $service
     * @param Condominium $condominium
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteCondominiumAction(
        Service $service,
        Condominium $condominium
    ) {
        $this->assertUserCanAccessService($service);

        $service->removeCondominium($condominium);
        $this->persistAndFlush($service);

        return $this->redirectToRoute(
            'service_condominium_list',
            ['service' => $service->getId()]
        );
    }

    /**
     * Adds a condominium to the served list of given service.
     *
     * @Route("/{condominium}", name="service_condominium_add")
     * @Method("POST")
     *
     * @param Service     $service
     * @param Condominium $condominium
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addCondominiumAction(
        Service $service,
        Condominium $condominium
    ) {
        $this->assertUserCanAccessService($service);

        $service->addCondominium($condominium);
        $this->persistAndFlush($service);

        return $this->redirectToRoute(
            'service_condominium_list',
            ['service' => $service->getId()]
        );
    }
}
