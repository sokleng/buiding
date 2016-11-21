<?php

namespace CondominiumManagementBundle\Controller;

use CondoBundle\Entity\Condominium;
use CondominiumManagementBundle\Traits\HasCondominiumManagementUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use CondoBundle\Traits\HasPagination;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CondoBundle\Entity\Income;
use CondoBundle\Entity\IncomeCategory;
use CondoBundle\Constant\InvoiceStatus;
use Symfony\Component\HttpFoundation\Response;
use DateTime;

/**
 * @Route("/{condominium}/income")
 */
class IncomeController extends Controller
{
    use HasCondominiumManagementUtils;
    use HasPagination;

    /**
     * List all income in condominium.
     *
     * @Route("/", name="condominium_income_list")
     * @Method("GET")
     * @Template("CondominiumManagementBundle:Income:list.html.twig")
     *
     * @param Condominium $condominium
     *
     * @return array
     */
    public function listAction(
        Request $request,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium);

        $form = $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumIncomeFilterType'
        );
        $form->handleRequest($request);

        $showBy = $form['showBy']->getData();
        $category = $form['category']->getData();
        $startDate = $form['startDate']->getData();
        $endDate = $form['endDate']->getData();

        $incomes = $this
            ->getIncomeRepository()
            ->findIncomeByCondominiumStatusCategoryAndDate(
                $condominium,
                intval($showBy),
                $category,
                $startDate,
                $endDate
            )
            ->getQuery()
            ->getResult();

        $incomesPagination = $this
            ->createPagination(
                $incomes,
                $request
            );

        return $this->getResponseParameters(
            [
                'condominium' => $condominium,
                'incomes' => $incomesPagination,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Create a new invoice for income.
     *
     * @Route("/new", name="condominium_income_new")
     * @Method({"POST", "GET"})
     * @Template("CondominiumManagementBundle:Income:new.html.twig")
     *
     * @param Request     $request
     * @param Condominium $condominium
     *
     * @return array
     */
    public function addAction(
        Request $request,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium);
        $income = new Income();

        $form = $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumIncomeInvoiceType',
            $income,
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
        $income->setSubTotal($subTotal);
        $income->setUsdAmount($usdAmount);
        $income->setCondominium($condominium);
        $income->setCurrency($condominium->getCurrency());
        $income->setCreateBy($this->getUser());
        $income->setExchangeSetting($condominium->getExchangeSetting());
        $this->persistAndFlush($income);

        return $this->redirectToRoute(
            'condominium_income_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );
    }

    /**
     * Edit a invoice for income.
     *
     * @Route("/edit/{income}", name="condominium_income_edit")
     * @Method({"POST", "GET"})
     * @Template("CondominiumManagementBundle:Income:edit.html.twig")
     *
     * @param Request     $request
     * @param Condominium $condominium
     * @param Income      $income
     *
     * @return array
     */
    public function editAction(
        Request $request,
        Condominium $condominium,
        Income $income
    ) {
        $this->assertCanAccessCondominium($condominium);

        $form = $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumIncomeInvoiceType',
            $income,
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
                    'income' => $income,
                    'currencies' => $currencies,
                ]
            );
        }

        $rate = $this
            ->getExchangeSettingRepository()
            ->getUSRate($condominium);
        $grandTotal = $form['grandTotal']->getData();
        $vat = $form['vat']->getData();
        $usdAmount = $grandTotal / $rate;
        $subTotal = $this
            ->getExchangeSettingRepository()
            ->getCalculateSubTotal(
                $grandTotal,
                $vat
            );
        $income->setSubTotal($subTotal);
        $income->setGrandTotal($grandTotal);
        $income->setUsdAmount($usdAmount);
        $income->setCondominium($condominium);

        $this->persistAndFlush($income);

        return $this->redirectToRoute(
            'condominium_income_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );
    }

    /**
     * Show a invoice for income.
     *
     * @Route("/show/{income}", name="condominium_income_show")
     * @Method("GET")
     * @Template("CondominiumManagementBundle:Income:show.html.twig")
     *
     * @param Condominium $condominium
     * @param Income      $income
     *
     * @return array
     */
    public function showAction(
        Condominium $condominium,
        Income $income
    ) {
        $this->assertCanAccessCondominium($condominium);

        $currencies = $this->getCurrencyRepository()
            ->findAllCurrencies()
            ->getQuery()
            ->getResult();

        return $this->getResponseParameters(
            [
                'condominium' => $condominium,
                'income' => $income,
                'currencies' => $currencies,
            ]
        );
    }

    /**
     * Delete a income invoice.
     *
     * @Route("/delete/{income}", name="condominium_income_delete")
     * @Method("GET")
     *
     * @param Condominium $condominium
     * @param Income      $income
     *
     * @return RedirectResponse
     */
    public function deleteAction(
        Condominium $condominium,
        Income $income
    ) {
        $this->assertCanAccessCondominium($condominium, $income);

        $this->removeAndFlush($income);

        return $this->redirectToRoute(
            'condominium_income_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );
    }

    /**
     * Mark income as paid.
     *
     * @Route("/paid/{income}", name="condominium_income_mark_as_paid")
     * @Method("POST")
     *
     * @param Condominium $condominium
     * @param Income      $income
     *
     * @return RedirectResponse
     */
    public function markAsPaidAction(
        Condominium $condominium,
        Income $income
    ) {
        $this->assertCanAccessCondominium($condominium, $income);

        if ($income->getStatus() === InvoiceStatus::UNPAID) {
            $income->setStatus(InvoiceStatus::PAID);
            $income->setMarkAsPaidBy($this->getUser());
            $income->setPaymentDate(new DateTime());
            $this->persistAndFlush($income);
        }

        return $this->redirectToRoute(
            'condominium_income_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );
    }

    /**
     * Rollback Income.
     *
     * @Route("/rollback/{income}", name="condominium_income_rollback")
     * @Method("POST")
     *
     * @param Condominium $condominium
     * @param Income      $income
     *
     * @return RedirectResponse
     */
    public function rollbackIncomeAction(
        Condominium $condominium,
        Income $income
    ) {
        $this->assertCanAccessCondominium($condominium, $income);

        if ($income->getStatus() === InvoiceStatus::PAID) {
            $income->setStatus(InvoiceStatus::UNPAID);
            $this->persistAndFlush($income);
        }

        return $this->redirectToRoute(
            'condominium_income_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );
    }

    /**
     * List all income category in condominium.
     *
     * @Route("/income_category", name="condominium_income_category_list")
     * @Method("GET")
     * @Template("CondominiumManagementBundle:Income:list_category.html.twig")
     *
     * @param Condominium $condominium
     *
     * @return array
     */
    public function listCategoryAction(
        Request $request,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium);

        $incomeCategories = $this
            ->getIncomeCategoryRepository()
            ->findAllIncomeCategoryByCondominium($condominium)
            ->getQuery()
            ->getResult();

        $incomeCategoriesPagination = $this
            ->createPagination(
                $incomeCategories,
                $request
            );

        return $this->getResponseParameters(
            [
                'condominium' => $condominium,
                'incomeCategories' => $incomeCategoriesPagination,
            ]
        );
    }

    /**
     * Create a new category for income.
     *
     * @Route("/income_category/new", name="condominium_income_category_new")
     * @Method({"POST", "GET"})
     * @Template("CondominiumManagementBundle:Income:new_category.html.twig")
     *
     * @param Request     $request
     * @param Condominium $condominium
     *
     * @return array
     */
    public function addCategoryAction(
        Request $request,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium);
        $incomeCategory = new IncomeCategory();
        $incomeCategory->setCondominium($condominium);

        $form = $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumIncomeCategoryType',
            $incomeCategory
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

        $this->persistAndFlush($incomeCategory);

        return $this->redirectToRoute(
            'condominium_income_category_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );
    }

    /**
     * Edit a category for income.
     *
     * @Route("/income_category/edit/{incomeCategory}", name="condominium_income_category_edit")
     * @Method({"POST", "GET"})
     * @Template("CondominiumManagementBundle:Income:edit_category.html.twig")
     *
     * @param Condominium $condominium
     *
     * @return array
     */
    public function editCategoryAction(
        Request $request,
        Condominium $condominium,
        IncomeCategory $incomeCategory
    ) {
        $this->assertCanAccessCondominium($condominium);

        $form = $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumIncomeCategoryType',
            $incomeCategory
        );
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return $this->getResponseParameters(
                [
                    'condominium' => $condominium,
                    'form' => $form->createView(),
                    'incomeCategory' => $incomeCategory,
                ]
            );
        }

        $this->persistAndFlush($incomeCategory);

        return $this->redirectToRoute(
            'condominium_income_category_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );
    }

    /**
     * Deletes a income category.
     *
     * @Route("/income_category/{incomeCategory}", name="condominium_income_category_delete")
     * @Method("DELETE")
     *
     * @param Condominium    $condominium
     * @param IncomeCategory $incomeCategory
     *
     * @return RedirectResponse
     */
    public function deleteCategoryAction(
        Condominium $condominium,
        IncomeCategory $incomeCategory
    ) {
        $this->assertCanAccessCondominium($condominium, $incomeCategory);

        $this->removeAndFlush($incomeCategory);

        return $this->redirectToRoute(
            'condominium_income_category_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );
    }

    /**
     * Download a income as pdf.
     *
     * @Route(
     *  "/download/invoice/{income}",
     *  name="condominium_income_download_invoice"
     * )
     *
     * @param Condominium $condominium
     * @param Income      $income
     * @param Request     $request
     *
     * @return Response
     */
    public function downloadInvoiceAction(
        Condominium $condominium,
        Income $income,
        Request $request
    ) {
        $html = $this->renderView(
            'CondominiumManagementBundle:Partial:invoice.html.twig',
            [
                'invoice' => $income,
                'base_dir' => $this->get('kernel')->getRootDir().'/../web'.
                $request->getBasePath(),
            ]
        );
        $filename = 'invoice'.$income->getId().'.pdf';

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
