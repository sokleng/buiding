<?php

namespace ServiceBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ServiceBundle\Traits\HasServiceControllerUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DashboardController extends Controller
{
    use HasServiceControllerUtils;

    /**
     * @Route("/", name="service_dashboard")
     * @Template("ServiceBundle:Dashboard:dashboard.html.twig")
     */
    public function dashboardAction()
    {
        return [
            'services' => $this->getManagerServices(),
        ];
    }
}
