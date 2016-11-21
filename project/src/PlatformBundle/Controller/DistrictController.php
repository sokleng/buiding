<?php

namespace PlatformBundle\Controller;

use CondoBundle\Entity\City;
use CondoBundle\Entity\Country;
use CondoBundle\Entity\District;
use CondoBundle\Traits\HasControllerUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/countries/{country}/cities/{city}/districts")
 */
class DistrictController extends Controller
{
    use HasControllerUtils;

    /**
     * Lists cities for a given country.
     *
     * @Route(path="/", name="admin_area_district")
     * @Method("GET")
     * @Template()
     *
     * @param Country $country
     * @param City    $city
     *
     * @return array
     */
    public function indexAction(
        Country $country,
        City $city
    ) {
        $districts = $this->getDistrictRepository()
            ->findBy(
                ['city' => $city],
                ['name' => 'ASC']
            );

        return [
            'country' => $country,
            'city' => $city,
            'districts' => $districts,
        ];
    }

    /**
     * Creates a Country and goes back to the country list.
     *
     * @Route("/", name="admin_areas_district_new")
     * @Method("POST")
     *
     * @param Request $request
     * @param Country $country
     * @param City    $city
     *
     * @return RedirectResponse
     */
    public function newCityAction(
        Request $request,
        Country $country,
        City    $city
    ) {
        $name = $request->get('name');

        if (!empty($name)) {
            $name = ucwords(strtolower($name));

            $district = $this->getDistrictRepository()
                ->findOneBy(['name' => $name]);

            if (empty($district)) {
                $district = new District();
                $district->setCity($city);
                $district->setName($name);
                $this->persistAndFlush($district);
            }
        }

        return $this->redirectToRoute(
            'admin_area_district',
            [
                'country' => $country->getId(),
                'city' => $city->getId(),
            ]
        );
    }
}
