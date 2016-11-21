<?php

namespace CondominiumManagementBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CondoBundle\Entity\IndividualSupplier;
use CondoBundle\Entity\CompanySupplier;
use CondoBundle\Entity\Condominium;
use CondoBundle\Traits\HasPagination;
use CondominiumManagementBundle\Traits\HasCondominiumManagementUtils;

/**
 * @Route("/{condominium}/supplier")
 */
class SupplierController extends Controller
{
    use HasCondominiumManagementUtils;
    use HasPagination;

    /**
     * List all company suppliers in condominium.
     *
     * @Route("/", name="condominium_suppliers_list")
     * @Method("GET")
     * @Template("CondominiumManagementBundle:Supplier:company_list.html.twig")
     *
     * @param Condominium $condominium
     *
     * @return array
     */
    public function companyListAction(
        Request $request,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium);
        $companySuppliers = $this
            ->getCompanySupplierRepository()
            ->findAllCompanySuppliersForCondo($condominium)
            ->getQuery()
            ->getResult();

        $companySuppliersPagination = $this
            ->createPagination(
                $companySuppliers,
                $request
            );

        return $this->getResponseParameters(
            [
                'condominium' => $condominium,
                'companySuppliers' => $companySuppliersPagination,
            ]
        );
    }

    /**
     * Create a new company supplier.
     *
     * @Route("/new", name="condominium_supplier_company_new")
     * @Method({"POST", "GET"})
     * @Template("CondominiumManagementBundle:Supplier:company_new.html.twig")
     *
     * @param Request     $request
     * @param Condominium $condominium
     *
     * @return array
     */
    public function companyAddAction(
        Request $request,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium);
        $companySupplier = new CompanySupplier();

        $form = $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumCompanySupplierType',
            $companySupplier
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
        $companySupplier->setCondominium($condominium);
        $this->persistAndFlush($companySupplier);

        return $this->redirectToRoute(
            'condominium_suppliers_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );
    }

    /**
     * Edit a company supplier.
     *
     * @Route("/{companySupplier}", name="condominium_supplier_company_edit")
     * @Method({"POST", "GET"})
     * @Template("CondominiumManagementBundle:Supplier:company_edit.html.twig")
     *
     * @param Request         $request
     * @param Condominium     $condominium
     * @param CompanySupplier $companySupplier
     *
     * @return array
     */
    public function companyEditAction(
        Request $request,
        Condominium $condominium,
        CompanySupplier $companySupplier
    ) {
        $this->assertCanAccessCondominium($condominium);
        $form = $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumCompanySupplierType',
            $companySupplier
        );
        $form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->getResponseParameters(
                [
                    'condominium' => $condominium,
                    'form' => $form->createView(),
                    'companySupplier' => $companySupplier,
                ]
            );
        }
        $companySupplier->setCondominium($condominium);
        $this->persistAndFlush($companySupplier);

        return $this->redirectToRoute(
            'condominium_suppliers_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );
    }

    /**
     * Deletes a company supplier.
     *
     * @Route("/{companySupplier}", name="condominium_supplier_company_delete")
     * @Method("DELETE")
     *
     * @param Condominium    $condominium
     * @param IncomeCategory $companySupplier
     *
     * @return RedirectResponse
     */
    public function companyDeleteAction(
        Condominium $condominium,
        CompanySupplier $companySupplier
    ) {
        $this->assertCanAccessCondominium($condominium, $companySupplier);

        $countIssues = $this->getIssueRepository()
            ->getCountIssuesForSupplier($companySupplier);
        if ($countIssues === 0) {
            $this->removeAndFlush($companySupplier);
        } else {
            $this->addFlash(
                'error',
                $this->get('translator')
                    ->trans('error.message.this.supplier.was.in.used')
            );
        }

        return $this->redirectToRoute(
            'condominium_suppliers_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );
    }

    /**
     * List all individual suppliers in condominium.
     *
     * @Route("/individual/list", name="condominium_suppliers_individual_list")
     * @Method("GET")
     * @Template("CondominiumManagementBundle:Supplier:individual_list.html.twig")
     *
     * @param Condominium $condominium
     *
     * @return array
     */
    public function individualListAction(
        Request $request,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium);
        $individualSuppliers = $this
            ->getIndividualSupplierRepository()
            ->findAllIndividualSuppliersForCondo($condominium)
            ->getQuery()
            ->getResult();

        $individualSuppliersPagination = $this
            ->createPagination(
                $individualSuppliers,
                $request
            );

        return $this->getResponseParameters(
            [
                'condominium' => $condominium,
                'individualSuppliers' => $individualSuppliersPagination,
            ]
        );
    }

    /**
     * Create a new individual supplier.
     *
     * @Route("/individual/new", name="condominium_individual_suppliers_new")
     * @Method({"POST", "GET"})
     * @Template("CondominiumManagementBundle:Supplier:individual_new.html.twig")
     *
     * @param Request     $request
     * @param Condominium $condominium
     *
     * @return array
     */
    public function individualAddAction(
        Request $request,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium);
        $individualSupplier = new IndividualSupplier();
        $form = $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumIndividualSupplierType',
            $individualSupplier
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
        $individualSupplier->setCondominium($condominium);
        $this->persistAndFlush($individualSupplier);

        return $this->redirectToRoute(
            'condominium_suppliers_individual_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );
    }

    /**
     * Edit a individual supplier.
     *
     * @Route("/individual/{individualSupplier}", name="condominium_individual_suppliers_edit")
     * @Method({"POST", "GET"})
     * @Template("CondominiumManagementBundle:Supplier:individual_edit.html.twig")
     *
     * @param Request            $request
     * @param Condominium        $condominium
     * @param IndividualSupplier $individualSupplier
     *
     * @return array
     */
    public function individualEditAction(
        Request $request,
        Condominium $condominium,
        IndividualSupplier $individualSupplier
    ) {
        $this->assertCanAccessCondominium($condominium);
        $form = $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumIndividualSupplierType',
            $individualSupplier
        );

        $form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->getResponseParameters(
                [
                    'condominium' => $condominium,
                    'form' => $form->createView(),
                    'individualSupplier' => $individualSupplier,
                ]
            );
        }
        $individualSupplier->setCondominium($condominium);
        $this->persistAndFlush($individualSupplier);

        return $this->redirectToRoute(
            'condominium_suppliers_individual_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );
    }

    /**
     * Deletes a individual supplier.
     *
     * @Route("/individual/{individualSupplier}", name="condominium_individual_suppliers_delete")
     * @Method("DELETE")
     *
     * @param Condominium        $condominium
     * @param IndividualSupplier $individualSupplier
     *
     * @return RedirectResponse
     */
    public function individualDeleteAction(
        Condominium $condominium,
        IndividualSupplier $individualSupplier
    ) {
        $this->assertCanAccessCondominium($condominium, $individualSupplier);

        $countIssues = $this->getIssueRepository()
            ->getCountIssuesForSupplier($individualSupplier);
        if ($countIssues === 0) {
            $this->removeAndFlush($individualSupplier);
        } else {
            $this->addFlash(
                'error',
                $this->get('translator')
                    ->trans('error.message.this.supplier.was.in.used')
            );
        }

        return $this->redirectToRoute(
            'condominium_suppliers_individual_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );
    }
}
