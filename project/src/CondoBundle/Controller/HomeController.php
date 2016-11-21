<?php

namespace CondoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomeController extends Controller
{
    /**
     * @Route("/", name="condo_homepage")
     */
    public function indexAction()
    {
        return $this->render('CondoBundle:Home:index.html.twig');
    }
}
