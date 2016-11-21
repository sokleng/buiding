<?php

namespace ClientBundle\Controller;

use ClientBundle\Traits\HasClientControllerUtils;
use CondoBundle\Entity\Unit;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UnitController extends Controller
{
    use HasClientControllerUtils;

    /**
     * @Route("/units/{unit}", name="client_unit_home")
     *
     * @Template("ClientBundle:Unit:index.html.twig")
     *
     * @param Unit $unit
     *
     * @return array
     */
    public function indexAction(
        Unit $unit
    ) {
        $this->assertCanAccessUnit($unit);
        $condominium = $unit->getCondominium();
        $user = $this->getUser();

        $services = $this->getServiceRepository()
            ->findAvailableServicesForCondominium($unit->getCondominium())
            ->getQuery()
            ->getResult();

        $issueCounts = $this->getIssueRepository()
            ->findCountByStatusForUserAndUnit($this->getUser(), $unit);

        $userLastThreeIssues = $this->getIssueRepository()
            ->findLastThreeIssuesOfUserThatNotYetResolved($condominium, $user);

        $feedback = $this->getFeedbackRepository()
            ->findLastFeedBackOfUserInCondominium($condominium, $user);

        return $this->getResponseParameters(
            [
                'unit' => $unit,
                'services' => $services,
                'issueCounts' => $issueCounts,
                'lastFeedback' => $feedback,
                'userLastThreeIssues' => $userLastThreeIssues,
            ]
        );
    }
}
