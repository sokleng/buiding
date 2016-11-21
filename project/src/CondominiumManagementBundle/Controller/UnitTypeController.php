<?php

namespace CondominiumManagementBundle\Controller;

use CondominiumManagementBundle\Traits\HasCondominiumManagementUtils;
use CondoBundle\Entity\UnitType;
use CondoBundle\Entity\Condominium;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use CondoBundle\Traits\HasPagination;

/**
 * @Route("/{condominium}/unit/types")
 */
class UnitTypeController extends Controller
{
    use HasCondominiumManagementUtils;
    use HasPagination;

    /**
     * @Route("/", name="condominium_unit_type_list")
     * @Method("GET")
     * @Template("CondominiumManagementBundle:UnitType:list.html.twig")
     *
     * @param Request     $request
     * @param Condominium $condominium
     *
     * @return array
     */
    public function listAction(
        Request $request,
        Condominium $condominium
    ) {
        $unitType = $this->getUnitTypeRepository()
            ->findAllUnitTypesForCondominium($condominium)
            ->getQuery()
            ->getResult()
        ;

        $unitTypePagination = $this->createPagination(
            $unitType,
            $request
        );

        return $this->getResponseParameters([
            'unitTypes' => $unitTypePagination,
            'condominium' => $condominium,
        ]);
    }

    /**
     * @Route("/new", name="condominium_unit_type_new")
     * @Method({"GET", "POST"})
     * @Template("CondominiumManagementBundle:UnitType:new.html.twig")
     *
     * @param Request     $request
     * @param Condominium $condominium
     *
     * @return array|RedirectResponse|Response
     */
    public function newAction(
        Request $request,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium);

        $unitType = new UnitType();

        $form = $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumUnitType',
            $unitType
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $unitType->setCondominium($condominium);
            $this->persistAndFlush($unitType);

            return $this->redirectToRoute(
                'condominium_unit_type_list',
                ['condominium' => $condominium->getId()]
            );
        }

        return $this->getResponseParameters(
            [
                'condominium' => $condominium,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{unitType}/edit", name="condominium_unit_type_edit")
     * @Method({"GET", "POST"})
     * @Template("CondominiumManagementBundle:UnitType:edit.html.twig")
     *
     * @param Request     $request
     * @param Condominium $condominium
     * @param UnitType    $unitType
     *
     * @return array|RedirectResponse|Response
     */
    public function editAction(
        Request $request,
        condominium $condominium,
        UnitType $unitType
    ) {
        $this->assertCanAccessCondominium($condominium, $unitType);

        $editForm = $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumUnitType',
            $unitType
        );
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->persistAndFlush($condominium);

            return $this->redirectToRoute(
                'condominium_unit_type_edit',
                [
                    'condominium' => $condominium->getId(),
                    'unitType' => $unitType->getId(),
                ]
            );
        }

        return $this->getResponseParameters([
            'condominium' => $condominium,
            'unitType' => $unitType,
            'form' => $editForm->createView(),
        ]);
    }

    /**
     * Deletes a unity type entity.
     *
     * @Route("/{unitType}", name="condominium_unit_type_delete")
     *
     * @param UnitType    $unitType
     * @param Condominium $condominium
     *
     * @return RedirectResponse
     */
    public function deleteAction(
        UnitType $unitType,
        Condominium $condominium
    ) {
        $unit = $this->getUnitRepository()
                ->findAllUnitForUnitType($unitType)
                ->getQuery()
                ->getResult()
        ;

        if (empty($unit)) {
            $this->removeAndFlush($unitType);
        } else {
            $this->addFlash(
                'error',

                $this->get('translator')->trans('flast.unitType.message.not.allow.user.to.delete.unitType.relationship.with.unit')
            );
        }

        return $this->redirectToRoute(
            'condominium_unit_type_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );
    }
}
