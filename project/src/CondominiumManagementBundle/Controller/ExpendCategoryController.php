<?php

namespace CondominiumManagementBundle\Controller;

use CondoBundle\Traits\HasPagination;
use CondoBundle\Entity\Condominium;
use CondoBundle\Entity\ExpendCategory;
use CondoBundle\Entity\Expend;
use CondominiumManagementBundle\Traits\HasCondominiumManagementUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CondoBundle\Constant\InvoiceStatus;
use Symfony\Component\HttpFoundation\Response;
use DateTime;

/**
 * @Route("/{condominium}/expend")
 */
class ExpendCategoryController extends Controller
{
    use HasCondominiumManagementUtils;
    use HasPagination;

    /**
     * List all expend in condominium.
     *
     * @Route("/category", name="condominium_expend_category_list")
     * @Method("GET")
     * @Template("CondominiumManagementBundle:ExpendCategory:list.html.twig")
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

        $expendCategorys = $this->getExpendCategoryRepository()
            ->findAllExpendByCondominium($condominium)
            ->getQuery()
            ->getResult();

        $expendCategorysPagination = $this->createPagination(
            $expendCategorys,
            $request
        );

        return $this->getResponseParameters(
            [
                'condominium' => $condominium,
                'expendCategorys' => $expendCategorysPagination,
            ]
        );
    }

    /**
     * @Route("/add", name="condominium_expend_category_new")
     * @Method({"GET", "POST"})
     * @Template("CondominiumManagementBundle:ExpendCategory:new.html.twig")
     *
     * @param Request     $request
     * @param condominium $condominium
     *
     * @return array|RedirectResponse
     */
    public function addAction(
        Request $request,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium);

        $expendCategory = new expendCategory();
        $expendCategory->setCondominium($condominium);

        $form = $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumExpendCategoryType',
            $expendCategory
        );

        $form->handleRequest($request);

        if (!$form->isSubmitted() && !$form->isValid()) {
            return $this->getResponseParameters(
                [
                    'condominium' => $condominium,
                    'form' => $form->createView(),
                ]
            );
        }

        $this->persistAndFlush($expendCategory);

        return $this->redirectToRoute(
            'condominium_expend_category_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing expend category entity.
     *
     * @Route("/{id}/edit", name="condominium_expend_category_edit")
     * @Method({"GET", "POST"})
     * @Template("CondominiumManagementBundle:ExpendCategory:edit.html.twig")
     *
     * @param Request        $request
     * @param ExpendCategory $expendCategory
     * @param Condominium    $condominium
     *
     * @return array|RedirectResponse
     */
    public function editAction(
        Request $request,
        ExpendCategory $expendCategory,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium);

        $form = $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumExpendCategoryType',
            $expendCategory
        );

        $form->handleRequest($request);

        if (!$form->isSubmitted() && !$form->isValid()) {
            return $this->getResponseParameters(
                [
                    'form' => $form->createView(),
                    'condominium' => $condominium,
                    'expendCategory' => $expendCategory,
                ]
            );
        }

        $this->persistAndFlush($expendCategory);

        return $this->redirectToRoute(
            'condominium_expend_category_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );
    }

    /**
     * Deletes a expend category.
     *
     * @Route("/{id}", name="condominium_expend_category_delete")
     * @Method("DELETE")
     *
     * @param ExpendCategory $expendCategory
     * @param Condominium    $condominium
     *
     * @return RedirectResponse
     */
    public function deleteAction(
        ExpendCategory $expendCategory,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium);

        $this->removeAndFlush($expendCategory);

        return $this->redirectToRoute(
            'condominium_expend_category_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );
    }

    /**
     * List all expend invoice in condominium.
     *
     * @Route("/", name="condominium_expend_invoice_list")
     * @Method("GET")
     * @Template("CondominiumManagementBundle:ExpendInvoice:list.html.twig")
     *
     * @param Request     $request
     * @param Condominium $condominium
     *
     * @return array
     */
    public function listInvoiceAction(
        Request $request,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium);

        $expendInvoices = $this->getExpendInvoiceRepository()
            ->findAllExpendInvoiceByCondominium($condominium)
            ->getQuery()
            ->getResult();
        $form = $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumExpendFilterType'
        );
        $form->handleRequest($request);

        $showBy = $form['showBy']->getData();
        $category = $form['category']->getData();
        $startDate = $form['startDate']->getData();
        $endDate = $form['endDate']->getData();

        $expendInvoices = $this->getExpendInvoiceRepository()
            ->findExpendByCondominiumStatusCategoryAndDate(
                $condominium,
                intval($showBy),
                $category,
                $startDate,
                $endDate
            )
            ->getQuery()
            ->getResult();

        $expendInvoicePagination = $this->createPagination(
            $expendInvoices,
            $request
        );

        return $this->getResponseParameters(
            [
                'condominium' => $condominium,
                'expendInvoices' => $expendInvoicePagination,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Show expend invoice.
     *
     * @Route("/show/{expendInvoice}", name="condominium_expend_invoice_show")
     * @Method("GET")
     * @Template("CondominiumManagementBundle:ExpendInvoice:show.html.twig")
     *
     * @param Expend      $expendInvoice
     * @param Condominium $condominium
     *
     * @return array
     */
    public function showInvoiceAction(
        Expend $expendInvoice,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium, $expendInvoice);

        return $this->getResponseParameters(
            [
                'condominium' => $condominium,
                'expendInvoice' => $expendInvoice,
            ]
        );
    }

    /**
     * @Route("/invoice/add", name="condominium_expend_invoice_new")
     * @Method({"GET", "POST"})
     * @Template("CondominiumManagementBundle:ExpendInvoice:new.html.twig")
     *
     * @param Request     $request
     * @param condominium $condominium
     *
     * @return array|RedirectResponse
     */
    public function addInvoiceAction(
        Request $request,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium);

        $expendInvoice = new Expend();

        $form = $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumExpendInvoiceType',
            $expendInvoice,
            [
                'condominium' => $condominium,
            ]
        );

        $currencies = $this->getCurrencyRepository()
            ->findAllCurrencies()
            ->getQuery()
            ->getResult();

        $form->handleRequest($request);

        if (
            !$form->isValid() ||
            $condominium->getExchangeSetting() === null
        ) {
            if (
                $condominium->getExchangeSetting() === null &&
                $form->isValid()
            ) {
                $this->addFlash(
                    'error',
                    $this->get('translator')
                        ->trans('error.message.unknown.exchange.rate')
                );
            }

            return $this->getResponseParameters(
                [
                    'condominium' => $condominium,
                    'form' => $form->createView(),
                    'currencies' => $currencies,
                ]
            );
        }

        $rate = $this
            ->getExchangeSettingRepository()
            ->getUSRate($condominium);
        $vat = $form['vat']->getData();
        $grandTotal = $form['grandTotal']->getData();
        $subTotal = $this
            ->getExchangeSettingRepository()
            ->getCalculateSubTotal(
                $grandTotal,
                $vat
            );
        $usdAmount = $grandTotal / $rate;
        $expendInvoice->setSubTotal($subTotal);
        $expendInvoice->setUsdAmount($usdAmount);
        $expendInvoice->setCondominium($condominium);
        $expendInvoice->setCurrency($condominium->getCurrency());
        $expendInvoice->setCreateBy($this->getUser());
        $expendInvoice->setExchangeSetting($condominium->getExchangeSetting());
        $this->persistAndFlush($expendInvoice);

        return $this->redirectToRoute(
            'condominium_expend_invoice_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing expend invoice entity.
     *
     * @Route("/{id}/invoice/edit", name="condominium_expend_invoice_edit")
     * @Method({"GET", "POST"})
     * @Template("CondominiumManagementBundle:ExpendInvoice:edit.html.twig")
     *
     * @param Request     $request
     * @param Expend      $expendInvoice
     * @param Condominium $condominium
     *
     * @return array|RedirectResponse
     */
    public function editInvoiceAction(
        Request $request,
        Expend $expendInvoice,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium);

        $form = $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumExpendInvoiceType',
            $expendInvoice,
            [
                'condominium' => $condominium,
            ]
        );

        $currencies = $this->getCurrencyRepository()
            ->findAllCurrencies()
            ->getQuery()
            ->getResult();

        $form->handleRequest($request);

        if (
            !$form->isValid() ||
            $condominium->getExchangeSetting() === null
        ) {
            if (
                $condominium->getExchangeSetting() === null &&
                $form->isValid()
            ) {
                $this->addFlash(
                    'error',
                    $this->get('translator')
                        ->trans('error.message.unknown.exchange.rate')
                );
            }

            return $this->getResponseParameters(
                [
                    'form' => $form->createView(),
                    'condominium' => $condominium,
                    'expendInvoice' => $expendInvoice,
                    'currencies' => $currencies,
                ]
            );
        }

        $rate = $this
            ->getExchangeSettingRepository()
            ->getUSRate($condominium);
        $vat = $form['vat']->getData();
        $grandTotal = $form['grandTotal']->getData();
        $subTotal = $this
            ->getExchangeSettingRepository()
            ->getCalculateSubTotal(
                $grandTotal,
                $vat
            );
        $usdAmount = $grandTotal / $rate;
        $expendInvoice->setSubTotal($subTotal);
        $expendInvoice->setUsdAmount($usdAmount);
        $this->persistAndFlush($expendInvoice);

        return $this->redirectToRoute(
            'condominium_expend_invoice_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );
    }

    /**
     * Deletes a expend invoice.
     *
     * @Route("/invoice/{id}/delete", name="condominium_expend_invoice_delete")
     * @Method("GET")
     *
     * @param Expend      $expendInvoice
     * @param Condominium $condominium
     *
     * @return RedirectResponse
     */
    public function deleteInvoiceAction(
        Expend $expendInvoice,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium);

        $this->removeAndFlush($expendInvoice);

        return $this->redirectToRoute(
            'condominium_expend_invoice_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );
    }

    /**
     * @Route("/{expendInvoice}/paid", name="condominium_expend_paid")
     * @Method("POST")
     *
     * @param Condominium $condominium
     * @param Expend      $expendInvoice
     *
     * @return RedirectResponse
     */
    public function markAsPaidAction(
        Condominium $condominium,
        Expend $expendInvoice
    ) {
        $this->assertCanAccessCondominium($condominium, $expendInvoice);

        if (!$expendInvoice->isPaid()) {
            $expendInvoice->setStatus(InvoiceStatus::PAID);
            $expendInvoice->setMarkAsPaidBy($this->getUser());
            $expendInvoice->setPaymentDate(new DateTime());
            $this->persistAndFlush($expendInvoice);
        }

        return $this->redirectToRoute(
            'condominium_expend_invoice_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );
    }

    /**
     * Rollback Expend.
     *
     * @Route("/rollback/{expendInvoice}", name="condominium_expense_rollback")
     *
     * @param Condominium $condominium
     * @param Expend      $expendInvoice
     *
     * @return RedirectResponse
     */
    public function rollbackExpendAction(
        Condominium $condominium,
        Expend $expendInvoice
    ) {
        $this->assertCanAccessCondominium($condominium, $expendInvoice);

        if ($expendInvoice->isPaid()) {
            $expendInvoice->setStatus(InvoiceStatus::UNPAID);
            $this->persistAndFlush($expendInvoice);
        }

        return $this->redirectToRoute(
            'condominium_expend_invoice_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );
    }

    /**
     * Download a expendInvoice as pdf.
     *
     * @Route(
     *  "/download/invoice/{expendInvoice}",
     *  name="condominium_expend_download_invoice"
     * )
     *
     * @param Condominium $condominium
     * @param Expend      $expendInvoice
     * @param Request     $request
     *
     * @return Response
     */
    public function downloadInvoiceAction(
        Condominium $condominium,
        Expend $expendInvoice,
        Request $request
    ) {
        $html = $this->renderView(
            'CondominiumManagementBundle:Partial:invoice.html.twig',
            [
                'invoice' => $expendInvoice,
                'base_dir' => $this->get('kernel')->getRootDir().'/../web'.
                $request->getBasePath(),
            ]
        );
        $filename = 'invoice'.$expendInvoice->getId().'.pdf';

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename='.$filename,
            ]
        );
    }
}
