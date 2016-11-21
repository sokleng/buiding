<?php

namespace ProjectRealtyBundle\Controller;

use ProjectRealtyBundle\Entity\CondominiumProject;
use ProjectRealtyBundle\Entity\ProjectUnitType;
use ProjectRealtyBundle\Traits\HasProjectControllerUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{project}/settings/unit/types")
 */
class UnitTypeController extends Controller
{
    use HasProjectControllerUtils;

    /**
     * @Route("/", name="project_unitType_list")
     * @Method("GET")
     * @Template("ProjectRealtyBundle:UnitType:list.html.twig")
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
     * @Route("/new", name="project_unitType_new")
     * @Method({"GET", "POST"})
     * @Template("ProjectRealtyBundle:UnitType:new.html.twig")
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

        $type = new ProjectUnitType();

        $form = $this->createForm(
            'ProjectRealtyBundle\Form\ProjectUnitTypeType',
            $type
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $type->setProject($project);
            $this->persistAndFlush($type);

            return $this->redirectToRoute(
                'project_unitType_list',
                ['project' => $project->getId()]
            );
        }

        return $this->getResponseParameters(
            [
                'project' => $project,
                'type' => $type,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Project Contact entity.
     *
     * @Route("/{type}/edit", name="project_unitType_edit")
     * @Method({"GET", "POST"})
     * @Template("ProjectRealtyBundle:UnitType:edit.html.twig")
     *
     * @param Request            $request
     * @param CondominiumProject $project
     * @param ProjectUnitType    $type
     *
     * @return array|RedirectResponse|Response
     */
    public function editAction(
        Request $request,
        CondominiumProject $project,
        ProjectUnitType $type
    ) {
        $this->assertCanAccessProject($project, $type);

        $editForm = $this->createForm(
            'ProjectRealtyBundle\Form\ProjectUnitTypeType',
            $type
        );
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->persistAndFlush($project);

            return $this->redirectToRoute(
                'project_unitType_edit',
                [
                    'project' => $project->getId(),
                    'type' => $type->getId(),
                ]
            );
        }

        return $this->getResponseParameters([
            'project' => $project,
            'type' => $type,
            'edit_form' => $editForm->createView(),
        ]);
    }
}
