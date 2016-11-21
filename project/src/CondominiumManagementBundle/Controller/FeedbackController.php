<?php

namespace CondominiumManagementBundle\Controller;

use CondoBundle\Traits\HasPagination;
use CondoBundle\Entity\Condominium;
use CondoBundle\Entity\Feedback;
use CondominiumManagementBundle\Traits\HasCondominiumManagementUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{condominium}/feedbacks")
 */
class FeedbackController extends Controller
{
    use HasCondominiumManagementUtils;
    use HasPagination;

    /**
     * Lists unread feedback in current condominium.
     *
     * @Route("/", name="condominium_feedback_list")
     * @Method("GET")
     * @Template("CondominiumManagementBundle:Feedback:list.html.twig")
     *
     * @param Request     $request
     * @param Condominium $condominium
     *
     * @return array
     */
    public function listAction(Request $request, Condominium $condominium)
    {
        $this->assertCanAccessCondominium($condominium);

        $feedbacks = $this->getFeedbackRepository()
            ->findAllByCondominium($condominium);

        $feedbacksPagination = $this->createPagination(
            $feedbacks,
            $request
        );

        return $this->getResponseParameters(
            [
                'feedbacks' => $feedbacksPagination,
                'condominium' => $condominium,
            ]
        );
    }

    /**
     * Show the specific feedback with condominium.
     *
     * @Route("/{feedback}", name="condominium_feedback_show")
     * @Method("GET")
     * @Template("CondominiumManagementBundle:Feedback:show.html.twig")
     *
     * @param Condominium $condominium
     * @param Feedback    $feedback
     *
     * @return array
     */
    public function showAction(
        Condominium $condominium,
        Feedback $feedback
    ) {
        $this->assertCanAccessCondominium($condominium, $feedback);

        return $this->getResponseParameters(
            [
                'feedback' => $feedback,
                'condominium' => $condominium,
            ]
        );
    }

    /**
     * @Route("/{feedback}/resolve", name="condominium_feedback_read")
     * @Method("POST")
     *
     * @param Condominium $condominium
     * @param Feedback    $feedback
     *
     * @return RedirectResponse
     */
    public function markAsReadAction(
        Condominium $condominium,
        Feedback $feedback
    ) {
        $this->assertCanAccessCondominium($condominium, $feedback);

        if (!$feedback->isRead()) {
            $feedback->setRead(true);

            $this->persistAndFlush($feedback);
        }

        return $this->redirectToRoute(
            'condominium_feedback_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );
    }
}
