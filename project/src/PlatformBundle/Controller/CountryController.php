<?php

namespace PlatformBundle\Controller;

use CondoBundle\Entity\Country;
use CondoBundle\Traits\HasControllerUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * District controller.
 *
 * @Route("/countries")
 */
class CountryController extends Controller
{
    use HasControllerUtils;

    /**
     * Lists all Countries.
     *
     * @Route("/", name="admin_area_country")
     * @Template(template="PlatformBundle:country:index.html.twig")
     * @Method("GET")
     */
    public function indexAction()
    {
        $countries = $this->getCountryRepository()->findAll();

        return ['countries' => $countries];
    }

    /**
     * Creates a Country and goes back to the country list.
     *
     * @Route("/", name="admin_areas_country_new")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function newCountryAction(Request $request)
    {
        $name = $request->get('name');
        $code = $request->get('code');

        if (!empty($name) && !empty($code)) {
            $name = ucwords(strtolower($name));
            $code = strtoupper($code);

            $country = $this->getCountryRepository()
                ->findOneBy(['name' => $name]);

            if (empty($country)) {
                $country = new Country();
                $country->setName($name);
                $country->setCode($code);
            }

            $this->persistAndFlush($country);
        }

        return $this->redirectToRoute('admin_area_country');
    }
}
