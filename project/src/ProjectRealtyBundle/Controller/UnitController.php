<?php

namespace ProjectRealtyBundle\Controller;

use ProjectRealtyBundle\Entity\CondominiumProject;
use ProjectRealtyBundle\Entity\ProjectUnit;
use ProjectRealtyBundle\Entity\ProjectUnitType;
use ProjectRealtyBundle\Traits\HasProjectControllerUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @Route("/{project}/units")
 */
class UnitController extends Controller
{
    use HasProjectControllerUtils;

    /**
     * @Route("/", name="project_units_list")
     * @Method("GET")
     * @Template("ProjectRealtyBundle:Unit:list.html.twig")
     *
     * @param CondominiumProject $project
     *
     * @return array
     */
    public function listAction(CondominiumProject $project)
    {
        $this->assertCanAccessProject($project);
        $units = $this->getProjectUnitRepository()
            ->findByProject($project)
            ->getQuery()
            ->getResult()
        ;

        return $this->getResponseParameters([
            'project' => $project,
            'units' => $units,
        ]);
    }

    /**
     * @Route("/", name="project_units_add")
     * @Method("POST")
     *
     * @param Request            $request
     * @param CondominiumProject $project
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(
        Request $request,
        CondominiumProject $project
    ) {
        $this->assertCanAccessProject($project);
        $result = $this->redirectToRoute(
            'project_units_list',
            ['project' => $project->getId()]
        );

        $roomNumber = $request->request->getInt('add_room_number');
        $unitTypeId = $request->request->getInt('add_unit_type');
        $unitFloor = $request->request->getInt('add_unit_floor');
        $unitPrice = $request->request->getInt('add_unit_price');

        // Checking all parameters are present
        if (
            empty($roomNumber) ||
            empty($unitTypeId) ||
            empty($unitFloor) ||
            empty($unitPrice)
        ) {
            return $result;
        }

        // Checking unit type id is correct
        /** @var ProjectUnitType $unitType */
        $unitType = $this->getProjectUnitTypeRepository()->find($unitTypeId);
        if (empty($unitType)) {
            return $result;
        }

        $unit = new ProjectUnit();
        $unit->setFloor($unitFloor)
            ->setType($unitType)
            ->setProject($project)
            ->setPrice($unitPrice)
            ->setRoomNumber($roomNumber)
        ;

        $this->persistAndFlush($unit);

        return $result;
    }

    /**
     * @Route("/{id}", name="project_units_show")
     * @Method("GET")
     * @Template("ProjectRealtyBundle:Unit:show.html.twig")
     *
     * @param ProjectUnit        $unit
     * @param CondominiumProject $project
     *
     * @return array
     */
    public function showAction(ProjectUnit $unit, CondominiumProject $project)
    {
        $this->assertCanAccessProject($project, $unit);
        $unitInfo = $this->getProjectUnitRepository()->find($unit);
        $deleteForm = $this->createDeleteForm($unit);

        return $this->getResponseParameters([
            'unit' => $unitInfo,
            'project' => $project,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Creates a form to delete a ProjectUnit entity.
     *
     * @param ProjectUnit $unit
     *
     * @return Form The form
     */
    private function createDeleteForm(ProjectUnit $unit)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl(
                'project_units_delete',
                [
                    'id' => $unit->getId(),
                    'project' => $unit->getProject()->getId(),
                ]
            ))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Deletes a ProjectUnit entity.
     *
     * @Route("/{id}", name="project_units_delete")
     * @Method("DELETE")
     *
     * @param Request            $request
     * @param ProjectUnit        $unit
     * @param CondominiumProject $project
     *
     * @return RedirectResponse
     */
    public function deleteAction(
        Request $request,
        ProjectUnit $unit,
        CondominiumProject $project
    ) {
        $this->assertCanAccessProject($project, $unit);
        $form = $this->createDeleteForm($unit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->removeAndFlush($unit);
        }

        return $this->redirectToRoute(
            'project_units_list',
            [
                'project' => $project->getId(),
            ]
        );
    }

    /**
     * @Route("/{unit}/edit", name="project_unit_edit")
     * @Method({"GET", "POST"})
     * @Template("ProjectRealtyBundle:Unit:edit.html.twig")
     *
     * @param Request            $request
     * @param CondominiumProject $project
     * @param ProjectUnit        $unit
     *
     * @return array|RedirectResponse
     */
    public function editAction(
        Request $request,
        CondominiumProject $project,
        ProjectUnit $unit
    ) {
        $this->assertCanAccessProject($project, $unit);

        $editForm = $this->createForm(
            'ProjectRealtyBundle\Form\ProjectUnitType',
            $unit
        );
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->persistAndFlush($project);

            return $this->redirectToRoute(
                'project_unit_edit',
                [
                    'project' => $project->getId(),
                    'unit' => $unit->getId(),
                ]
            );
        }

        return $this->getResponseParameters([
            'project' => $project,
            'unit' => $unit,
            'edit_form' => $editForm->createView(),
        ]);
    }
}
