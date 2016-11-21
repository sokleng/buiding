<?php

namespace FrontendBundle\Controller;

use CondoBundle\Traits\HasPagination;
use CondoBundle\Traits\HasControllerUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use ProjectRealtyBundle\Entity\CondoProjectListingProfile;
use Symfony\Component\HttpFoundation\Request;

class FrontendController extends Controller
{
    const PAGINATION = 8;
    const FRONTEND_PAGINATION_TEMPLATE = 'frontendPagination.html.twig';

    use HasControllerUtils;
    use HasPagination;

    /**
     * Lists all Condominium in frontend homepage.
     *
     * @Route("/home", name="homepage")
     * @Template("FrontendBundle:Home:index.html.twig")
     *
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $getQueryString = $request->query->get('type');

        $projectPublicListings = $this->getCondoProjectListingProfileRepository()
            ->findProjectPublicListing($getQueryString)
            ->getQuery()
            ->getResult()
        ;

        $projectPublicListingsPagination = $this->createPagination(
            $projectPublicListings,
            $request,
            self::PAGINATION,
            self::FRONTEND_PAGINATION_TEMPLATE
        );

        return [
            'projectPublicListings' => $projectPublicListingsPagination,
        ];
    }

    /**
     * @Route("/project/{project}/unit-types", name="unit_types")
     * @Method("GET")
     * @Template("FrontendBundle:Unit:index.html.twig")
     *
     * @param CondoProjectListingProfile $project
     *
     * @return array
     */
    public function UnitTypesIndexAction(CondoProjectListingProfile $project)
    {
        return [
            'condoProject' => $project,
        ];
    }

    /**
     * Lists all Condominium in frontend give search.
     *
     * @Route("/search-map", name="map_search")
     * @Template("FrontendBundle:Frontend:searchMap.html.twig")
     *
     * @param Request $request
     *
     * @Method("GET")
     */
    public function searchMap(Request $request)
    {
        $getQueryString = $request->query->get('type');

        $em = $this->getDoctrine()->getManager();

        $projectPublicListings = $this->getCondoProjectListingProfileRepository()
            ->findProjectPublicListing($getQueryString)
                ->getQuery()
                ->getResult()
            ;

        $this->getParameter('google_maps_api_key');

        return [
            'projectPublicListings' => $projectPublicListings,
        ];
    }
}
