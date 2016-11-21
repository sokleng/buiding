<?php

namespace ProjectRealtyBundle\Controller;

use ProjectRealtyBundle\Entity\CondominiumProject;
use ProjectRealtyBundle\Entity\ProjectUnitStatus;
use ProjectRealtyBundle\Traits\HasProjectControllerUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/{project}/settings/unit/status")
 */
class UnitStatusController extends Controller
{
    use HasProjectControllerUtils;

    /**
     * @Route("/", name="project_unitStatus_list")
     * @Method("GET")
     * @Template("ProjectRealtyBundle:UnitStatus:list.html.twig")
     *
     * @param CondominiumProject $project
     *
     * @return array
     */
    public function listAction(
        CondominiumProject $project
    ) {
        return $this->getResponseParameters([
           'project' => $project,
        ]);
    }

    /**
     * @Route("/new", name="project_unitStatus_new")
     * @Method({"GET", "POST"})
     * @Template("ProjectRealtyBundle:UnitStatus:new.html.twig")
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

        $status = new ProjectUnitStatus();

        $form = $this->createForm(
            'ProjectRealtyBundle\Form\ProjectUnitStatusType',
            $status
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $status->setProject($project);
            $this->persistAndFlush($status);

            return $this->redirectToRoute(
                'project_unitStatus_list',
                ['project' => $project->getId()]
            );
        }

        return $this->getResponseParameters(
            [
                'project' => $project,
                'status' => $status,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Project Contact entity.
     *
     * @Route("/{status}/edit", name="project_unitStatus_edit")
     * @Method({"GET", "POST"})
     * @Template("ProjectRealtyBundle:UnitStatus:edit.html.twig")
     *
     * @param Request            $request
     * @param CondominiumProject $project
     * @param ProjectUnitStatus  $status
     *
     * @return array|RedirectResponse|Response
     */
    public function editAction(
        Request $request,
        CondominiumProject $project,
        ProjectUnitStatus $status
    ) {
        $this->assertCanAccessProject($project, $status);

        $editForm = $this->createForm(
            'ProjectRealtyBundle\Form\ProjectUnitStatusType',
            $status
        );
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->persistAndFlush($project);

            return $this->redirectToRoute(
                'project_unitStatus_edit',
                [
                    'project' => $project->getId(),
                    'status' => $status->getId(),
                ]
            );
        }

        return $this->getResponseParameters([
            'project' => $project,
            'status' => $status,
            'edit_form' => $editForm->createView(),
        ]);
    }
}
