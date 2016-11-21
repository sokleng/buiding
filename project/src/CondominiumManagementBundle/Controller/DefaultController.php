<?php

namespace CondominiumManagementBundle\Controller;

use CondoBundle\Entity\Condominium;
use CondominiumManagementBundle\Traits\HasCondominiumManagementUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    use HasCondominiumManagementUtils;

    /**
     * Handles default route and most appropriate redirection for the base condo
     * management space.
     *
     * @Route("/", name="condominium_defaults")
     */
    public function indexAction()
    {
        /** @var Condominium[] $condominiums */
        $condominiums = $this->getCondominiumRepository()
            ->findManagerCondominiums($this->getUser())
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        if (empty($condominiums)) {
            throw new \LogicException(
                'Accessing condominium space without condominiums found.'
            );
        }

        return $this->redirectToRoute(
            'condominium_dashboard',
            ['condominium' => $condominiums[0]->getId()]
        );
    }
}
