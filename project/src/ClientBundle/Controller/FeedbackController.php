<?php

namespace ClientBundle\Controller;

use ClientBundle\Traits\HasClientControllerUtils;
use CondoBundle\Entity\Feedback;
use CondoBundle\Entity\Unit;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/units/{unit}/feedback")
 */
class FeedbackController extends Controller
{
    use HasClientControllerUtils;

    /**
     * Creates a new issue entity.
     *
     * @Route("/new", name="client_feedback_new")
     * @Method({"GET", "POST"})
     * @Template("ClientBundle:Feedback:new.html.twig")
     *
     * @param Request $request
     * @param Unit    $unit
     *
     * @return array|RedirectResponse
     */
    public function newAction(Request $request, Unit $unit)
    {
        $this->assertCanAccessUnit($unit);

        $feedback = new Feedback();
        $feedback->setCondominium($unit->getCondominium());
        $feedback->setUser($this->getUser());
        $form = $this->createForm('ClientBundle\Form\FeedbackType', $feedback);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($feedback);
            $em->flush();

            return $this->redirectToRoute(
                'client_unit_home',
                [
                    'unit' => $unit->getId(),
                ]
            );
        }

        return $this->getResponseParameters(
            [
                'unit' => $unit,
                'form' => $form->createView(),
            ]
        );
    }
}
