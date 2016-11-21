<?php

namespace CondominiumManagementBundle\Controller;

use CondoBundle\Traits\HasPagination;
use CondoBundle\Constant\IssueStatus;
use CondoBundle\Entity\Condominium;
use CondoBundle\Entity\Issue;
use CondoBundle\Entity\IssueComment;
use CondoBundle\Entity\Expend;
use CondominiumManagementBundle\Traits\HasCondominiumManagementUtils;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use WeBridge\UserBundle\Entity\User;
use CondoBundle\Constant\InvoiceStatus;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/{condominium}/issues")
 */
class IssueController extends Controller
{
    use HasCondominiumManagementUtils;
    use HasPagination;

    const PROGRESS = 'progress';
    const CLOSED = 'closed';

    /**
     * Lists open issues in current condominium.
     *
     * @Route("/", name="condominium_issues_list")
     * @Method("GET")
     * @Template("CondominiumManagementBundle:Issue:list.html.twig")
     *
     * @param Request     $request
     * @param Condominium $condominium
     *
     * @return array
     */
    public function listAction(Request $request, Condominium $condominium)
    {
        $this->assertCanAccessCondominium($condominium);
        $issueCounts = $this->getIssueRepository()
            ->findCountByStatusForCondominium($condominium);

        $issueStatus = empty($request->query->get('status')) ?
            IssueStatus::OPEN_AND_IN_PROGRESS :
            $request->query->get('status');
        $issues = [];
        if ($issueStatus === 'all') {
            $issues = $this->getIssueRepository()
                ->findAllIssueCondominium($condominium);
        } else {
            $issues = $this->getIssueRepository()
                ->filterIssueByStatus($condominium, $issueStatus);
        }

        $issuesPagination = $this->createPagination(
            $issues,
            $request
        );

        return $this->getResponseParameters(
            [
                'issues' => $issuesPagination,
                'condominium' => $condominium,
                'status' => $issueStatus,
                'issueCounts' => $issueCounts,
            ]
        );
    }

    /**
     * Gets user who created issues.
     *
     * @Template("CondominiumManagementBundle:Issue:client.html.twig")
     *
     * @param Condominium $condominium
     * @param User        $user
     *
     * @return array
     */
    public function getUserPhoneAction(
        Condominium $condominium,
        User $user
    ) {
        $issueUsers = $this->getClientUnitRepository()
            ->findUserByCondoAndUser($condominium, $user);

        return $this->getResponseParameters(
            [
                'issueUsers' => $issueUsers,
            ]
        );
    }

    /**
     * Shows details for a specific issue in the condominium management space.
     *
     * @Route("/{issue}", name="condominium_issues_show")
     * @Method({"GET","POST"})
     * @Template("CondominiumManagementBundle:Issue:show.html.twig")
     *
     * @param Condominium $condominium
     * @param Issue       $issue
     *
     * @return array
     */
    public function showAction(
        Request $request,
        Condominium $condominium,
        Issue $issue
    ) {
        $this->assertCanAccessCondominium($condominium, $issue->getUnit());

        $currencies = $this->getCurrencyRepository()
            ->findAllCurrencies()
            ->getQuery()
            ->getResult();

        $form = $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumIssueType',
            $issue
        );

        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($condominium->getExchangeSetting() !== null) {
                $this->saveIssueAction(
                    $condominium,
                    $issue,
                    $form
                );
            } else {
                $this->addFlash(
                    'error',
                    $this->get('translator')
                        ->trans('error.message.unknown.exchange.rate')
                );
            }
        }

        return $this->getResponseParameters(
            [
                'issue' => $issue,
                'condominium' => $condominium,
                'currencies' => $currencies,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Save issue when use click mark in progress or closed.
     *
     * @param Condominium $condominium
     * @param Issue       $issue
     * @param $form
     */
    public function saveIssueAction(
        Condominium $condominium,
        Issue $issue,
        $form
    ) {
        $actionType = $form['actionType']->getData();
        $supplierId = $form['supplierId']->getData();
        $supplier = $this->getSupplierRepository()->find($supplierId);
        $issue->setSupplier($supplier);
        $issue->setCurrency($condominium->getCurrency());
        if ($condominium->isVat()) {
            $issue->setVat($condominium->getRate());
        }
        if ($condominium->getExchangeSetting() !== null) {
            $issue->setExchangeSetting($condominium->getExchangeSetting());
        }
        if ($actionType === self::PROGRESS) {
            if ($issue->isOpen()) {
                $issue->setStatus(IssueStatus::IN_PROGRESS);
            }
        }
        if ($actionType === self::CLOSED) {
            if ($issue->isOpen()) {
                $issue->setStatus(IssueStatus::CLOSED);
                $issue->setClosingDate(new DateTime());
            }
        }

        $this->persistAndFlush($issue);
    }

    /**
     * @Route("{issue}/issue-invoice", name="condominium_issues_invoice")
     * @Method({"GET", "POST"})
     *
     * @param Request     $request
     * @param condominium $condominium
     * @param Issue       $issue
     *
     * @return array|RedirectResponse
     */
    public function addIssueInvoiceAction(
        Request $request,
        Condominium $condominium,
        Issue $issue
    ) {
        $this->assertCanAccessCondominium($condominium, $issue->getId());

        $expend = new Expend();
        $price = $issue->getPrice();
        $getTitle = explode(' ', $issue->getDescription());
        $issueDescription = $issue->getDescription();
        $title = $getTitle[0];

        if (isset($getTitle[1])) {
            $title .= ' '.$getTitle[1];
        }
        if (isset($getTitle[2])) {
            $title .= ' '.$getTitle[2];
        }

        $expend->setCondominium($condominium);

        if ($issue->isInprogress() or ($issue->isClosed() and $price !== null)) {
            if ($issue->isIssueInvoice()) {
                return $this->redirectToRoute(
                    'condominium_expend_invoice_list',
                    [
                        'condominium' => $condominium->getId(),
                        'issue' => $issue->getId(),
                    ]
                );
            }

            $rate = $this
                ->getExchangeSettingRepository()
                ->getUSRate($condominium);
            $usdAmount = $price / $rate;
            $expend->setTitle($title);
            $expend->setSubTotal($price);
            $expend->setGrandTotal($price);
            $expend->setIssue($issue);
            $expend->setDescription($issueDescription);
            $expend->setStatus(InvoiceStatus::UNPAID);
            $expend->setCurrency($issue->getcurrency());
            $expend->setExchangeSetting($issue->getExchangeSetting());
            $expend->setVat($issue->getVat());
            $expend->setUsdAmount($usdAmount);

            $this->persistAndFlush($expend);
        }

        return $this->redirectToRoute(
            'condominium_expend_invoice_list',
            [
                'condominium' => $condominium->getId(),
                'issue' => $issue->getId(),
            ]
        );
    }

    /**
     * Adds a new comment to a given issue for the current user.
     *
     * @Route("/{issue}/comments", name="condominium_issues_comments_new")
     * @Method("POST")
     *
     * @param Request     $request
     * @param Condominium $condominium
     * @param Issue       $issue
     *
     * @return RedirectResponse
     */
    public function postCommentAction(
        Request $request,
        Condominium $condominium,
        Issue $issue
    ) {
        $this->assertCanAccessCondominium($condominium, $issue);

        $commentText = $request->get('issue_comment');
        if (!empty($commentText) && $issue->isOpen()) {
            $comment = new IssueComment();
            $comment->setUser($this->getUser());
            $comment->setIssue($issue);
            $comment->setReadByResident(false);
            $comment->setReadByManagement(true);
            $comment->setComment($commentText);

            $this->persistAndFlush($comment);
        }

        return $this->redirectToRoute(
            'condominium_issues_show',
            [
                'condominium' => $condominium->getId(),
                'issue' => $issue->getId(),
            ]
        );
    }

    /**
     * @Route(
     *      "/get/suppliers/",
     *      defaults={"unit" = null},
     *      name="condominium_get_suppliers"
     * )
     *
     * @param Request     $request
     * @param Condominium $condominium
     *
     * @return JsonResponse
     */
    public function getSuppliersAction(
        Request     $request,
        Condominium $condominium
    ) {
        $supplierType = intval($request->get('supplierType'));
        $name = $request->get('term');

        if ($supplierType === 0) {
            $suppliers = $this
                ->getCompanySupplierRepository()
                ->findAllCompanySuppliersForNameAndCondo(
                    $condominium,
                    $name
                )
                ->getQuery()
                ->getResult();
        }

        if ($supplierType === 1) {
            $suppliers = $this
                ->getIndividualSupplierRepository()
                ->findAllIndividualSuppliersForNameAndCondo(
                    $condominium,
                    $name
                )
                ->getQuery()
                ->getResult();
        }

        $data = [];
        foreach ($suppliers as $supplier) {
            $data[] = [
                'value' => $supplier->getName(),
                'id' => $supplier->getId(),
            ];
        }

        return new JsonResponse(
            $data
        );
    }


    /**
     * @Route(
     *      "/get/suppliersInfo/",
     *      name="condominium_get_suppliers_info"
     * )
     *
     * @param Request     $request
     * @param Condominium $condominium
     *
     * @return JsonResponse
     */
    public function getSuppliersInfoAction(
        Request     $request,
        Condominium $condominium
    ) {

        $id = $request->get('id');
        $supplierInfo = $this->getSupplierRepository()->find($id);
        $supplierGmail = $supplierInfo->getEmail();
        $supplierPhone = $supplierInfo->getPhoneNumber();
        $supplierAddress = $supplierInfo->getAddress();

        return new JsonResponse(
           [
            'supplierGmail' => $supplierGmail,
            'supplierPhone' => $supplierPhone,
            'supplierAddress' => $supplierAddress
           ]
        );
    }
}
