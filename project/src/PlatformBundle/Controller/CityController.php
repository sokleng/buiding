<?php

namespace PlatformBundle\Controller;

use CondoBundle\Entity\City;
use CondoBundle\Entity\Country;
use CondoBundle\Traits\HasControllerUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/countries/{country}/cities")
 */
class CityController extends Controller
{
    use HasControllerUtils;

    /**
     * Lists cities for a given country.
     *
     * @Route(path="/", name="admin_area_city")
     * @Method("GET")
     * @Template()
     *
     * @param Country $country
     *
     * @return array
     */
    public function indexAction(Country $country)
    {
        $cities = $this->getCityRepository()
            ->findBy(
                ['country' => $country],
                ['name' => 'ASC']
            );

        return [
            'country' => $country,
            'cities' => $cities,
        ];
    }

    /**
     * Creates a Country and goes back to the country list.
     *
     * @Route("/", name="admin_areas_city_new")
     * @Method("POST")
     *
     * @param Request $request
     * @param Country $country
     *
     * @return RedirectResponse
     */
    public function newCityAction(
        Request $request,
        Country $country
    ) {
        $name = $request->get('name');

        if (!empty($name)) {
            $name = ucwords(strtolower($name));

            $city = $this->getCityRepository()
                ->findOneBy(['name' => $name]);

            if (empty($city)) {
                $city = new City();
                $city->setCountry($country);
                $city->setName($name);
                $this->persistAndFlush($city);
            }
        }

        return $this->redirectToRoute(
            'admin_area_city',
            ['country' => $country->getId()]
        );
    }

    /**
     * Delete a city.
     *
     * @Route("/{city}", name="admin_areas_city_delete")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param Country $country
     * @param City    $city
     *
     * @return RedirectResponse
     */
    public function deleteAction(
        Request $request,
        Country $country,
        City $city
    ) {
        $form = $this->createDeleteForm($country, $city);
        $form->handleRequest($request);
        $this->removeAndFlush($city);

        return $this->redirectToRoute(
            'admin_area_city',
            ['country' => $country->getId()]
        );
    }

    /**
     * Creates a form to delete a city.
     *
     * @param Country $country
     * @param City    $city
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Country $country, City $city)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'admin_areas_city_delete',
                    [
                        'country' => $country->getId(),
                        'city' => $city->getId(),
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
