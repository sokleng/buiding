<?php

namespace RealtyCompanyBundle\Controller;

use RealtyCompanyBundle\Entity\RealtyCompany;
use RealtyCompanyBundle\Traits\HasRealtyControllerUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use DeveloperBundle\Entity\DeveloperPayment;
use Symfony\Component\HttpFoundation\Request;
use ProjectRealtyBundle\Constant\PaymentStatus;

/**
 * @Route("/{company}/payments")
 */
class PaymentController extends Controller
{
    use HasRealtyControllerUtils;
    /**
     * @Route("/", name="payments_list")
     * @Template("RealtyCompanyBundle:Payment:list.html.twig")
     */
    public function listAction(RealtyCompany $company)
    {
        $this->assertCanAccessRealtyCompany($company);
        $payments = $this->getDeveloperPaymentRepository()
            ->findAllByRealtyCompany($company)
            ->getQuery()
            ->getResult();

        return $this->getResponseParameters([
            'company' => $company,
            'payments' => $payments,
        ]);
    }

    /**
     * @Route("/new", name="developer_payments_new")
     * @Method({"GET", "POST"})
     * @Template("RealtyCompanyBundle:Payment:new.html.twig")
     *
     * @param Request       $request
     * @param RealtyCompany $company
     *
     * @return array|RedirectResponse
     */
    public function newAction(
        Request $request,
        RealtyCompany $company
    ) {
        $this->assertCanAccessRealtyCompany($company);

        $payment = new DeveloperPayment();

        $form = $this->createForm(
            'DeveloperBundle\Form\DeveloperPaymentType',
            $payment
        );
        $form->handleRequest($request);
        if (!$form->isSubmitted() && !$form->isValid()) {
            return $this->getResponseParameters(
                [
                    'company' => $company,
                    'payment' => $payment,
                    'form' => $form->createView(),
                ]
            );
        }
        $payment->setRealtyCompany($company);
        $this->persistAndFlush($payment);
        $company->getDeveloperPayments()->add($payment);
        $this->persistAndFlush($company);

        return $this->redirectToRoute(
            'payments_list',
            ['company' => $company->getId()]
        );
    }

    /**
     * Displays a form to edit an existing DeveloperPayment entity.
     *
     * @Route("/{payment}/edit", name="payments_edit")
     * @Method({"GET", "POST"})
     * @Template("RealtyCompanyBundle:Payment:edit.html.twig")
     *
     * @param Request          $request
     * @param RealtyCompany    $company
     * @param DeveloperPayment $payment
     *
     * @return array|RedirectResponse
     */
    public function editAction(
        Request $request,
        RealtyCompany $company,
        DeveloperPayment $payment
    ) {
        if (!$payment->isStatusDraft()) {
            throw new AccessDeniedException(
                'Can edit only payment whose satus is draft'
            );
        }
        $this->assertCanAccessRealtyCompany($company, $payment);

        $deleteForm = $this->createDeleteForm($company, $payment);
        $editForm = $this->createForm(
            'DeveloperBundle\Form\DeveloperPaymentType',
            $payment
        );
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->persistAndFlush($payment);

            return $this->redirectToRoute(
                'payments_edit',
                [
                    'company' => $company->getId(),
                    'payment' => $payment->getId(),
                ]
            );
        }

        return $this->getResponseParameters([
            'company' => $company,
            'payment' => $payment,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Creates a form to delete a DeveloperPayment entity.
     *
     * @param RealtyCompany    $company
     * @param DeveloperPayment $payment
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(
        RealtyCompany $company,
        DeveloperPayment $payment
    ) {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'payments_delete',
                    [
                        'company' => $company->getId(),
                        'payment' => $payment->getId(),
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Deletes a DeveloperPayment entity.
     *
     * @Route("/{payment}", name="payments_delete")
     * @Method("DELETE")
     *
     * @param Request          $request
     * @param RealtyCompany    $company
     * @param DeveloperPayment $payment
     *
     * @return RedirectResponse
     */
    public function deleteAction(
        Request $request,
        RealtyCompany $company,
        DeveloperPayment $payment
    ) {
        if (!$payment->isStatusDraft()) {
            throw new AccessDeniedException(
                'Can delete only payment whose satus is draft'
            );
        }
        $this->assertCanAccessRealtyCompany($company, $payment);
        $form = $this->createDeleteForm($company, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->removeAndFlush($payment);
        }

        return $this->redirectToRoute(
            'payments_list',
            ['company' => $company->getId()]
        );
    }

    /**
     * Finds and displays a Developer Payment entity.
     *
     * @Route("/{payment}", name="payments_show")
     * @Method("GET")
     * @Template("RealtyCompanyBundle:Payment:show.html.twig")
     *
     * @param DeveloperPayment $payment
     * @param RealtyCompany    $company
     *
     * @return array
     */
    public function showAction(DeveloperPayment $payment, RealtyCompany $company)
    {
        $this->assertCanAccessRealtyCompany($company, $payment);
        $paymentInfo = $this->getDeveloperPaymentRepository()->find($payment);
        $deleteForm = $this->createDeleteForm($company, $payment);

        return $this->getResponseParameters([
            'payment' => $paymentInfo,
            'company' => $company,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Cancel the Developer Payment.
     *
     * @Route("/{payment}/cancel", name="payments_cancel")
     * @Method("GET")
     * @Template("RealtyCompanyBundle:Payment:list.html.twig")
     *
     * @param DeveloperPayment $payment
     * @param RealtyCompany    $company
     *
     * @return array
     */
    public function CancelPaymentAction(
        DeveloperPayment $payment,
        RealtyCompany $company
    ) {
        $this->assertCanAccessRealtyCompany($company, $payment);
        $paymentInfo = $this->getDeveloperPaymentRepository()->find($payment);
        $payments = $this->getDeveloperPaymentRepository()
            ->findAllByRealtyCompany($company)
            ->getQuery()
            ->getResult();
        $paymentInfo->setStatus(PaymentStatus::CANCELLED);
        $this->persistAndFlush($paymentInfo);

        return $this->getResponseParameters([
            'payment' => $paymentInfo,
            'payments' => $payments,
            'company' => $company,
        ]);
    }

    /**
     * Mark Developer Payment as Paid.
     *
     * @Route("/{payment}/paid", name="payments_paid")
     * @Method("GET")
     * @Template("RealtyCompanyBundle:Payment:list.html.twig")
     *
     * @param DeveloperPayment $payment
     * @param RealtyCompany    $company
     *
     * @return array
     */
    public function MarkPaymentAsPaidAction(
        DeveloperPayment $payment,
        RealtyCompany $company
    ) {
        $this->assertCanAccessRealtyCompany($company, $payment);
        $paymentInfo = $this->getDeveloperPaymentRepository()->find($payment);
        $payments = $this->getDeveloperPaymentRepository()
            ->findAllByRealtyCompany($company)
            ->getQuery()
            ->getResult();
        $paymentInfo->setStatus(PaymentStatus::PAID);
        $this->persistAndFlush($paymentInfo);

        return $this->getResponseParameters([
            'payment' => $paymentInfo,
            'payments' => $payments,
            'company' => $company,
        ]);
    }
}
