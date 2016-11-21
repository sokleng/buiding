<?php

namespace ClientBundle\Controller;

use ClientBundle\Traits\HasClientControllerUtils;
use CondoBundle\Entity\Resident;
use CondoBundle\Entity\Unit;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomeController extends Controller
{
    use HasClientControllerUtils;

    /**
     * @Route("/", name="client_home")
     *
     * @Template("ClientBundle:Home:index.html.twig")
     */
    public function indexAction()
    {
        $user = $this->getUser();

        /** @var Resident[] $residents */
        $residents = $this->getResidentRepository()
            ->findBy(
                ['user' => $user]
            );

        return $this->redirectToRoute(
            'client_unit_home',
            [
                'unit' => $residents[0]->getUnit()->getId(),
            ]
        );
    }
}
