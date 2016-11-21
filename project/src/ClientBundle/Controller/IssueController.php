<?php

namespace ClientBundle\Controller;

use ClientBundle\Traits\HasClientControllerUtils;
use CondoBundle\Traits\HasPagination;
use CondoBundle\Constant\IssueStatus;
use CondoBundle\Entity\Issue;
use CondoBundle\Entity\IssueComment;
use CondoBundle\Entity\Unit;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use CondoBundle\Entity\DatabaseFile;

/**
 * @Route("/units/{unit}/issues")
 */
class IssueController extends Controller
{
    use HasClientControllerUtils;
    use HasPagination;

    /**
     * @Route("/", name="client_issues_list")
     * @Method("GET")
     * @Template("ClientBundle:Issue:list.html.twig")
     *
     * @param Request $request
     * @param Unit    $unit
     *
     * @return array
     */
    public function listAction(Request $request, Unit $unit)
    {
        $this->assertCanAccessUnit($unit);

        $issues = $this->getIssueRepository()
            ->findByUnit($unit);

        $issuesPagination = $this->createPagination(
            $issues,
            $request
        );

        return $this->getResponseParameters(
            [
                'unit' => $unit,
                'issues' => $issuesPagination,
            ]
        );
    }

    /**
     * Creates a new issue entity.
     *
     * @Route("/new", name="client_issues_new")
     * @Method({"GET", "POST"})
     * @Template("ClientBundle:Issue:new.html.twig")
     *
     * @param Request $request
     * @param Unit    $unit
     *
     * @return array|RedirectResponse
     */
    public function newAction(Request $request, Unit $unit)
    {
        $this->assertCanAccessUnit($unit);

        $issue = new Issue();
        $issue->setUnit($unit);
        $issue->setUser($this->getUser());
        $issue->setStatus(IssueStatus::OPEN);
        $form = $this->createForm('ClientBundle\Form\IssueType', $issue);
        $form->handleRequest($request);
        $files = $form->get('databaseFiles')->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($issue);
            $em->flush();

            if (!empty($files)) {
                foreach ($files as $file) {
                    if ($file === null) {
                        continue;
                    }
                    $databaseFile = new DatabaseFile($file);
                    $databaseFile->setIssue($issue);
                    $this->persistAndFlush($databaseFile);
                }
            }

            return $this->redirectToRoute(
                'client_issues_list',
                [
                    'unit' => $unit->getId(),
                ]
            );
        }

        return $this->getResponseParameters(
            [
                'unit' => $unit,
                'issue' => $issue,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Shows details on an given issue for the current user.
     *
     * @Route("/{issue}", name="client_issues_show")
     * @Method("GET")
     * @Template("ClientBundle:Issue:show.html.twig")
     *
     * @param Unit  $unit
     * @param Issue $issue
     *
     * @return array
     */
    public function showAction(Unit $unit, Issue $issue)
    {
        $this->assertCanAccessUnit($unit, $issue);

        return $this->getResponseParameters(
            [
                'unit' => $unit,
                'issue' => $issue,
            ]
        );
    }

    /**
     * Adds a new comment to a given issue for the current user.
     *
     * @Route("/{issue}/comments", name="client_issues_comments_new")
     * @Method("POST")
     *
     * @param Request $request
     * @param Unit    $unit
     * @param Issue   $issue
     *
     * @return RedirectResponse
     */
    public function postCommentAction(
        Request $request,
        Unit $unit,
        Issue $issue
    ) {
        $this->assertCanAccessUnit($unit, $issue);

        $commentText = $request->get('issue_comment');
        if (!empty($commentText) && $issue->isOpen()) {
            $comment = new IssueComment();
            $comment->setUser($this->getUser());
            $comment->setIssue($issue);
            $comment->setReadByResident(true);
            $comment->setReadByManagement(false);
            $comment->setComment($commentText);

            $this->persistAndFlush($comment);
        }

        return $this->redirectToRoute(
            'client_issues_show',
            [
                'issue' => $issue->getId(),
                'unit' => $unit->getId(),
            ]
        );
    }

    /**
     * Sets a given issue to cancelled.
     *
     * @Route("/{issue}/status", name="client_issues_cancel")
     * @Method("DELETE")
     *
     * @param Unit  $unit
     * @param Issue $issue
     *
     * @return RedirectResponse
     */
    public function cancelAction(
        Unit $unit,
        Issue $issue
    ) {
        $this->assertCanAccessUnit($unit, $issue);

        if ($issue->isOpen()) {
            $issue->setStatus(IssueStatus::CANCELLED);
            $issue->setClosingDate(new DateTime());
            $this->persistAndFlush($issue);
        }

        return $this->redirectToRoute(
            'client_issues_show',
            [
                'issue' => $issue->getId(),
                'unit' => $unit->getId(),
            ]
        );
    }
}
