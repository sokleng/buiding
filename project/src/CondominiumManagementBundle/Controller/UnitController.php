<?php

namespace CondominiumManagementBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use CondoBundle\Entity\Condominium;
use CondoBundle\Entity\Unit;
use CondoBundle\Entity\UnitPriceLog;
use CondominiumManagementBundle\Traits\HasCondominiumManagementUtils;
use CondoBundle\Traits\HasPagination;

/**
 * @Route("/{condominium}/units")
 */
class UnitController extends Controller
{
    use HasCondominiumManagementUtils;
    use HasPagination;

    /**
     * @Route("/", name="condominium_units_list")
     * @Method("GET")
     * @Template("CondominiumManagementBundle:Unit:list.html.twig")
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
        $this->assertCanAccessCondominium($condominium);
        $units = $this->getUnitRepository()
            ->findAllUnitsForCondominium($condominium)
            ->getQuery()
            ->getResult()
        ;

        $unitsPagination = $this->createPagination(
            $units,
            $request
        );

        $unitTypes = $this->getUnitTypeRepository()
            ->findAllUnitTypesForCondominium($condominium)
            ->getQuery()
            ->getResult()
        ;

        return $this->getResponseParameters([
            'condominium' => $condominium,
            'units' => $unitsPagination,
            'unitTypes' => $unitTypes,
        ]);
    }

    /**
     * @Route("/new", name="condominium_units_add")
     * @Method({"GET", "POST"})
     * @Template("CondominiumManagementBundle:Unit:new.html.twig")
     *
     * @param Request     $request
     * @param Condominium $condominium
     *
     * @return array|RedirectResponse|Response
     */
    public function addAction(
        Request $request,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium);
        $unit = new Unit();

        $form = $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumUnit',
            $unit
        );
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return $this->getResponseParameters(
                [
                    'condominium' => $condominium,
                    'form' => $form->createView(),
                ]
            );
        }

        $unit->setCondominium($condominium);
        $this->persistAndFlush($unit);

        return $this->redirectToRoute(
            'condominium_units_list',
            ['condominium' => $condominium->getId()]
        );
    }

    /**
     * @Route("/{unit}", name="condominium_units_show")
     * @Method("GET")
     * @Template("CondominiumManagementBundle:Unit:show.html.twig")
     *
     * @param Unit        $unit
     * @param Condominium $condominium
     *
     * @return array
     */
    public function showAction(Unit $unit, Condominium $condominium)
    {
        $this->assertCanAccessCondominium($condominium, $unit);
        $unitInfo = $this->getUnitRepository()->find($unit);

        return $this->getResponseParameters([
            'unit' => $unitInfo,
            'condominium' => $condominium,
        ]);
    }

    /**
     * Creates a form to delete a Unit entity.
     *
     * @param Unit $unit
     *
     * @return Form The form
     */
    private function createDeleteForm(Unit $unit)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl(
                'condominium_unit_delete',
                [
                    'id' => $unit->getId(),
                    'condominium' => $unit->getCondominium()->getId(),
                ]
            ))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Deletes a Unit entity.
     *
     * @Route("/{unit}/delete", name="condominium_unit_delete")
     * @Method("GET")
     *
     * @param Request     $request
     * @param Condominium $condominium
     * @param Unit        $unit
     *
     * @return RedirectResponse
     */
    public function deleteAction(
        Condominium $condominium,
        Unit $unit
    ) {
        $unitClient = $this->getClientUnitRepository()->findUnitClientByUnit($unit);
        $unitIssue = $this->getIssueRepository()->findUnitIssueByUnit($unit);
        if (empty($unitClient) && empty($unitIssue)) {
            $this->removeAndFlush($unit);
        } else {
            $this->addFlash(
                'error',
                $this->get('translator')
                    ->trans('error.message.can.not.delete.unit')
            );
        }

        return $this->redirectToRoute(
            'condominium_units_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );
    }

    /**
     * @Route("/{unit}/edit", name="condominium_unit_edit")
     * @Method({"GET", "POST"})
     * @Template("CondominiumManagementBundle:Unit:edit.html.twig")
     *
     * @param Request     $request
     * @param Condominium $condominium
     * @param Unit        $unit
     *
     * @return array|RedirectResponse
     */
    public function editAction(
        Request $request,
        Condominium $condominium,
        Unit $unit
    ) {
        $this->assertCanAccessCondominium($condominium, $unit);

        $form = $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumUnit',
            $unit
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($condominium);

            return $this->redirectToRoute(
                'condominium_unit_edit',
                [
                    'condominium' => $condominium->getId(),
                    'unit' => $unit->getId(),
                ]
            );
        }

        return $this->getResponseParameters([
            'condominium' => $condominium,
            'unit' => $unit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/update/price", name="condominium_unit_update_price")
     * @Method("POST")
     *
     * @param Condominium $condominium
     * @param Request     $request
     *
     * @return JsonResponse
     */
    public function updatePriceAction(
        Request $request,
        Condominium $condominium
    ) {
        $unitId = $request->get('unitId');
        $price = $request->get('price');
        $oldPrice = $request->get('oldPrice');
        $unit = $this->getUnitRepository()->find($unitId);
        if(empty($unit)){
            return new JsonResponse(
                [
                    'status' => 500,
                    'message' => $this->get('translator')
                        ->trans('unit.not.found')
                ]
            );
        }

        $unit->setPrice($price);
        $this->persistAndFlush($unit);

        $unitPriceLog = new UnitPriceLog();
        $unitPriceLog->setOldPrice($oldPrice);
        $unitPriceLog->setNewPrice($price);
        $unitPriceLog->setUnit($unit);
        $unitPriceLog->setEditBy($this->getUser());
        $this->persistAndFlush($unitPriceLog);

        return new JsonResponse(
            [
                'status' => 200,
                'price' => number_format($price, 2),
            ]
        );
    }
}
