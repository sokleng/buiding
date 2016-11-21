<?php

namespace ProjectRealtyBundle\Controller;

use ProjectRealtyBundle\Entity\CondominiumProject;
use ProjectRealtyBundle\Entity\ProjectPayment;
use ProjectRealtyBundle\Traits\HasProjectControllerUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{project}/payments")
 */
class PaymentController extends Controller
{
    use HasProjectControllerUtils;

    /**
     * @Route("/", name="project_payments_list")
     * @Method("GET")
     * @Template("ProjectRealtyBundle:Payment:list.html.twig")
     *
     * @param CondominiumProject $project
     *
     * @return array
     */
    public function listAction(
        CondominiumProject $project
    ) {
        $this->assertCanAccessProject($project);

        $payments = $this->getProjectPaymentRepository()
            ->findAllByProject($project)
            ->getQuery()
            ->getResult()
        ;

        return $this->getResponseParameters(
            [
                'project' => $project,
                'payments' => $payments,
            ]
        );
    }

    /**
     * Creates a new Project Payment.
     *
     * @Route("/new", name="project_payments_new")
     * @Method({"GET", "POST"})
     * @Template("ProjectRealtyBundle:Payment:new.html.twig")
     *
     * @param Request            $request
     * @param CondominiumProject $project
     *
     * @return RedirectResponse|Response|array
     */
    public function newAction(
        Request $request,
        CondominiumProject $project
    ) {
        $this->assertCanAccessProject($project);

        $payment = new ProjectPayment();
        $form = $this->createForm(
            'ProjectRealtyBundle\Form\ProjectPaymentType',
            $payment,
            [
                'units' => $this->getProjectUnitRepository()->findByProject($project),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($payment);

            return $this->redirectToRoute(
                'project_payments_show',
                [
                    'project' => $project->getId(),
                    'id' => $payment->getId(),
                ]
            );
        }

        return $this->getResponseParameters([
            'project' => $project,
            'payment' => $payment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing ProjectPayment entity.
     *
     * @Route("/{payment}/edit", name="project_payments_edit")
     * @Method({"GET", "POST"})
     * @Template("ProjectRealtyBundle:Payment:edit.html.twig")
     *
     * @param Request            $request
     * @param CondominiumProject $project
     * @param ProjectPayment     $payment
     *
     * @return array|RedirectResponse
     */
    public function editAction(
        Request $request,
        CondominiumProject $project,
        ProjectPayment $payment
    ) {
        $this->assertCanAccessProject($project, $payment);

        $deleteForm = $this->createDeleteForm($project, $payment);
        $editForm = $this->createForm(
            'ProjectRealtyBundle\Form\ProjectPaymentType',
            $payment,
            [
                'units' => $this->getProjectUnitRepository()->findByProject($project),
            ]
        );
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->persistAndFlush($payment);

            return $this->redirectToRoute(
                'project_payments_edit',
                [
                    'project' => $project->getId(),
                    'payment' => $payment->getId(),
                ]
            );
        }

        return $this->getResponseParameters([
            'project' => $project,
            'payment' => $payment,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Finds and displays a Project Payment entity.
     *
     * @Route("/{id}", name="project_payments_show")
     * @Method("GET")
     * @Template("ProjectRealtyBundle:Payment:show.html.twig")
     *
     * @param ProjectPayment     $payment
     * @param CondominiumProject $project
     *
     * @return array
     */
    public function showAction(ProjectPayment $payment, CondominiumProject $project)
    {
        $this->assertCanAccessProject($project, $payment);
        $paymentInfo = $this->getProjectPaymentRepository()->find($payment);
        $deleteForm = $this->createDeleteForm($project, $payment);

        return $this->getResponseParameters([
            'payment' => $paymentInfo,
            'project' => $project,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Creates a form to delete a ProjectPayment entity.
     *
     * @param CondominiumProject $project
     * @param ProjectPayment     $payment The ProjectPayment entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(
        CondominiumProject $project,
        ProjectPayment $payment
    ) {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'project_payments_delete',
                    [
                        'project' => $project->getId(),
                        'payment' => $payment->getId(),
                    ]
                )
            )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Deletes a ProjectPayment entity.
     *
     * @Route("/{payment}", name="project_payments_delete")
     * @Method("DELETE")
     *
     * @param Request            $request
     * @param CondominiumProject $project
     * @param ProjectPayment     $payment
     *
     * @return RedirectResponse
     */
    public function deleteAction(
        Request $request,
        CondominiumProject $project,
        ProjectPayment $payment
    ) {
        $this->assertCanAccessProject($project, $payment);
        $form = $this->createDeleteForm($project, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->removeAndFlush($payment);
        }

        return $this->redirectToRoute(
            'project_payments_list',
            ['project' => $project->getId()]
        );
    }
}
