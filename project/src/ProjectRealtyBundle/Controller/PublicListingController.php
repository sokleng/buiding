<?php

namespace ProjectRealtyBundle\Controller;

use ProjectRealtyBundle\Entity\CondominiumProject;
use ProjectRealtyBundle\Entity\CondoProjectListingProfile;
use ProjectRealtyBundle\Traits\HasProjectControllerUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CondoBundle\Entity\DatabaseFile;

/**
 * @Route("/{project}/settings/public-listing")
 */
class PublicListingController extends Controller
{
    use HasProjectControllerUtils;

    /**
     * @Route("/", name="project_public_listing_list")
     * @Method("GET")
     * @Template("ProjectRealtyBundle:PublicListing:list.html.twig")
     *
     * @param CondominiumProject $project
     *
     * @return array
     */
    public function listAction(
        CondominiumProject $project
    ) {
        if ($project->getProjectListing() == null) {
            return $this->redirectToRoute(
                'project_public_listing_new',
                [
                    'project' => $project->getId(),
                ]
            );
        } else {
            return $this->redirectToRoute(
                'project_public_listing_edit',
                [
                    'project' => $project->getId(),
                    'publicListing' => $project->getProjectListing()->getId(),
                ]
            );
        }
    }

    /**
     * @Route("/new", name="project_public_listing_new")
     * @Method({"GET", "POST"})
     * @Template("ProjectRealtyBundle:PublicListing:new.html.twig")
     *
     * @param Request            $request
     * @param CondominiumProject $project
     *
     * @return array|RedirectResponse|Response
     */
    public function newAction(
        Request $request,
        CondominiumProject $project
    ) {
        $this->assertCanAccessProject($project);

        $publicListing = new CondoProjectListingProfile();

        $form = $this->createForm(
            'ProjectRealtyBundle\Form\ProjectPublicListingType',
            $publicListing
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('databaseFile')->getData();
            $databaseFile = new DatabaseFile($file);
            $this->persistAndFlush($databaseFile);

            $publicListing->setProject($project);
            $publicListing->setDatabaseFile($databaseFile);
            $this->persistAndFlush($publicListing);

            return $this->redirectToRoute(
                'project_public_listing_list',
                ['project' => $project->getId()]
            );
        }

        return $this->getResponseParameters(
            [
                'project' => $project,
                'publicListing' => $publicListing,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Project Contact entity.
     *
     * @Route("/{publicListing}/edit", name="project_public_listing_edit")
     * @Method({"GET", "POST"})
     * @Template("ProjectRealtyBundle:PublicListing:edit.html.twig")
     *
     * @param Request                    $request
     * @param CondominiumProject         $project
     * @param CondoProjectListingProfile $publicListing
     *
     * @return array|RedirectResponse|Response
     */
    public function editAction(
        Request $request,
        CondominiumProject $project,
        CondoProjectListingProfile $publicListing
    ) {
        $this->assertCanAccessProject($project, $publicListing);

        $editForm = $this->createForm(
            'ProjectRealtyBundle\Form\ProjectPublicListingType',
            $publicListing
        );
        $editForm->handleRequest($request);

        if (!$editForm->isSubmitted() && !$editForm->isValid()) {
            return $this->getResponseParameters([
                'project' => $project,
                'publicListing' => $publicListing,
                'form' => $editForm->createView(),
            ]);
        }

        $file = $editForm->get('databaseFile')->getData();
        if ($file !== null) {
            $databaseFile = new DatabaseFile($file);
            $this->persistAndFlush($databaseFile);
            $publicListing->setDatabaseFile($databaseFile);
        }

        $this->persistAndFlush($publicListing);

        return $this->redirectToRoute(
            'project_public_listing_list',
            [
                'project' => $project->getId(),
            ]
        );
    }
}
