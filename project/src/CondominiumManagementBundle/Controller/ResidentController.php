<?php

namespace CondominiumManagementBundle\Controller;

use CondoBundle\Entity\DatabaseFile;
use CondoBundle\Entity\Condominium;
use WeBridge\UserBundle\Entity\User;
use CondoBundle\Traits\HasPagination;
use CondominiumManagementBundle\Traits\HasCondominiumManagementUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use CondoBundle\Constant\SecurityRole;
use CondoBundle\Entity\Unit;
use CondoBundle\Entity\ClientUnit;
use Symfony\Component\HttpFoundation\JsonResponse;
use CondominiumManagementBundle\Constant\ClientStatus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use CondoBundle\Entity\Income;
use CondoBundle\Entity\ExchangeSetting;
use CondoBundle\Constant\InvoiceStatus;
use DateTime;

/**
 * @Route("/{condominium}/client")
 */
class ResidentController extends Controller
{
    use HasCondominiumManagementUtils;
    use HasPagination;

    const DAY = '1';
    const MONTH = '2';
    const CONDOMINIUM_INVOICE_FILTER = 'condominium_invoice_filter';

    /**
     * @Route("/", name="condominium_residents_list")
     * @Template("CondominiumManagementBundle:Resident:list.html.twig")
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

        $form = $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumClientUnitFilterType'
        );

        $form->handleRequest($request);

        if (!$form->isSubmitted() && !$form->isValid()) {
            $startDate = '';
            $endDate = '';
            $status = ClientStatus::STAYING;

            return $this->clientDataList(
                $condominium,
                $status,
                $startDate,
                $endDate,
                $request,
                $form
            );
        }

        $status = $form['showBy']->getData();
        $startDate = $form['startDate']->getData();
        $endDate = $form['endDate']->getData();

        return $this->clientDataList(
            $condominium,
            $status,
            $startDate,
            $endDate,
            $request,
            $form
        );
    }

    private function clientDataList(
        $condominium,
        $status,
        $startDate,
        $endDate,
        $request,
        $form
    ) {
        $users = $this->getClientUnitRepository()
            ->findClientUnitByStatusDateAndCondo(
                $condominium,
                $status,
                $startDate,
                $endDate
            );

        $userPagination = $this->createPagination(
            $users,
            $request
        );

        return $this->getResponseParameters([
            'clients' => $userPagination,
            'condominium' => $condominium,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Add a User entity.
     *
     * @Route("/add", name="condominium_client_add")
     * @Template("CondominiumManagementBundle:Resident:new.html.twig")
     *
     * @param Request     $request
     * @param Condominium $condominium
     *
     * @return RedirectResponse
     */
    public function addClientAction(
        Request $request,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium);
        $user = new User();
        $clientUnit = new ClientUnit();
        $form = $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumClientType',
            [
                'user' => $user,
                'clientUnit' => $clientUnit,
            ],
            [
                'condominium' => $condominium,
            ]

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

        //User Info
        $email = $form['user']['email']->getData();
        $user->setUsername($email);
        $user->setRoles([SecurityRole::CLIENT]);
        $user->setEnabled(true);

        $clientUnit->setCurrency($condominium->getCurrency());
        if ($condominium->getExchangeSetting() !== null) {
            $clientUnit->setExchangeSetting($condominium->getExchangeSetting());
        }

        //Client Unit Info
        $this->clientUnitData(
            $form,
            $request,
            $clientUnit,
            $condominium
        );

        //Checking user exist register
        $userExistRegister = $this->getUserRepository()->findUserByEmail($email);
        $returnClientAdd = $this->redirectToRoute(
            'condominium_client_add',
            ['condominium' => $condominium->getId()]
        );
        if (!empty($userExistRegister)) {
            $this->addFlash(
                'warning',
                $this->get('translator')
                    ->trans('error.message.this.email.already.exist')
            );

            return $returnClientAdd;
        }

        try {
            $this->persistUser($request, $user, $clientUnit);
        } catch (\Exception $e) {
            $this->addFlash(
                'error',
                $this->get('translator')
                    ->trans('error.message.invalid.user.form')
            );

            return $returnClientAdd;
        }

        return $this->redirectToRoute(
            'condominium_residents_list',
            ['condominium' => $condominium->getId()]
        );
    }

    /**
     * Edit User entity.
     *
     * @Route("/edit/{clientUnit}/{user}", name="condominium_client_edit")
     * @Template("CondominiumManagementBundle:Resident:edit.html.twig")
     *
     * @param Request     $request
     * @param Condominium $condominium
     * @param User        $user
     * @param ClientUnit  $clientUnit
     *
     * @return RedirectResponse
     */
    public function editClientAction(
        Request $request,
        Condominium $condominium,
        User $user,
        ClientUnit $clientUnit
    ) {
        $form = $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumClientType',
            [
                'user' => $user,
                'clientUnit' => $clientUnit,
            ],
            [
                'condominium' => $condominium,
                'unit' => $clientUnit->getUnit(),
            ]

        );
        $form->handleRequest($request);

        if (!$form->isSubmitted() && !$form->isValid()) {
            return $this->getResponseParameters(
                [
                    'condominium' => $condominium,
                    'user' => $user,
                    'clientUnit' => $clientUnit,
                    'form' => $form->createView(),
                ]
            );
        }

        //User Info
        $email = $user->getUsername();
        $user->setEmail($email);
        $user->setEmailCanonical($email);

        //Client Unit Info
        $this->clientUnitData(
            $form,
            $request,
            $clientUnit,
            $condominium
        );

        try {
            $this->persistUser($request, $user, $clientUnit);
        } catch (\Exception $e) {
            $this->addFlash(
                'error',
                $this->get('translator')
                    ->trans('error.message.invalid.user.form')
            );

            return $this->redirectToRoute(
                'condominium_client_edit',
                [
                    'condominium' => $condominium->getId(),
                    'user' => $user->getId(),
                    'clientUnit' => $clientUnit->getId(),
                ]
            );
        }

        return $this->redirectToRoute(
            'condominium_residents_list',
            ['condominium' => $condominium->getId()]
        );
    }

    private function clientUnitData(
        $form,
        $request,
        $clientUnit,
        $condominium
    ) {
        $picture = $form['clientUnit']['picture']->getData();
        $idCardPicture = $form['clientUnit']['idCardPicture']->getData();
        $startDate = $form['clientUnit']['startDate']->getData();
        $endDate = $form['clientUnit']['endDate']->getData();
        $payBy = $request->request->get('payBy');
        $rentalPrice = $request->request->get('rentalPrice');
        $unitPrice = $request->request->get('unitPrice');
        $startDate = new DateTime($startDate);
        $endDate = new DateTime($endDate);
        $clientUnit->setStartDate($startDate);
        $clientUnit->setEndDate($endDate);
        $clientUnit->setRentalPrice($rentalPrice);

        if ($picture !== null) {
            $databasefile = new DatabaseFile($picture);
            $this->persistAndFlush($databasefile);
            $clientUnit->setPicture($databasefile);
        }

        if ($idCardPicture !== null) {
            $databasefile = new DatabaseFile($idCardPicture);
            $this->persistAndFlush($databasefile);
            $clientUnit->setIdCardPicture($databasefile);
        }

        if ($payBy !== null) {
            $clientUnit->setPaymentMethod($payBy);
        }

        if ($unitPrice !== null) {
            $clientUnit->setUnitPrice($unitPrice);
        }

        if ($condominium->getRate() !== null) {
            $clientUnit->setVat($condominium->getRate());
        }

        if ($form['clientUnit']['isRunScheduleAuto']->getData()) {
            $clientUnit->setDay($request->request->get('day'));
            $clientUnit->setHour($request->request->get('hour'));
        }
    }

    private function persistUser(
        $request,
        $user,
        $clientUnit
    ) {
        //persist user
        $this->persistAndFlush($user);

        //persit client unit
        $clientUnit->setUser($user);
        $this->persistAndFlush($clientUnit);
        if ($clientUnit->generatedInvoice()) {
            $invoice = new Income();
            $this->invoiceData(
                $clientUnit,
                $clientUnit->getUnit()->getCondominium(),
                $invoice,
                $request
            );
        }
    }

    /**
     * @Route("/search", name="condominium_client_search")
     *
     * @param Condominium $condominium
     */
    public function searchClient(
        Request $request,
        Condominium $condominium
    ) {
        $result = $this->redirectToRoute(
            'condominium_client_add',
            ['condominium' => $condominium->getId()]
        );

        $email = $request->request->get('search_client_email');

        // Checking all parameters are present
        if (empty($email)) {
            return $result;
        }

        //Search user exist or not
        $user = $this->getUserRepository()->findUserByEmail($email);

        if (!empty($user)) {
            $clientUnit = $this->getClientUnitRepository()->findClientUnitByUser($user);
            if (!empty($clientUnit) &&
                $clientUnit->getUnit()->getCondominium()->getId() !=
                $condominium->getId()
            ) {
                return $this->redirectToRoute(
                    'condominium_client_edit',
                    [
                        'user' => $user->getId(),
                        'clientUnit' => $clientUnit->getId(),
                        'condominium' => $condominium->getId(),
                    ]
                );
            }

            $this->addFlash(
                'warning',
                $this->get('translator')
                    ->trans('error.message.this.email.already.exist')
            );

            return $result;
        }

        return $result;
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/{user}/{clientUnit}", name="condominium_client_delete")
     *
     * @param User        $user
     * @param Condominium $condominium
     *
     * @return RedirectResponse
     */
    public function deleteAction(
        User $user,
        Condominium $condominium,
        ClientUnit $clientUnit
    ) {
        $clientList = $this->redirectToRoute(
            'condominium_residents_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );

        try {
            $this->removeAndFlush($clientUnit);
            $this->removeAndFlush($user);
        } catch (\Exception $e) {
            $this->addFlash(
                'error',
                $this->get('translator')
                    ->trans('error.message.user.can.not.delete')
            );

            return $clientList;
        }

        return $clientList;
    }

    /**
     * @Route(
     *  "/get/unit/{unit}",
     *  defaults={"unit" = null},
     *  name="condominium_unit_get"
     * )
     *
     * @param Unit $unit
     *
     * @return JsonResponse
     */
    public function getUnitAction(Unit $unit)
    {
        $unit = $this->getUnitRepository()->find($unit);
        if (!empty($unit)) {
            return new JsonResponse(
                [
                    'isLocked' => $unit->isLocked(),
                    'payBy' => $unit->getPayBy(),
                    'price' => $unit->getPrice(),
                ]
            );
        }

        return new JsonResponse([]);
    }

    /**
     * @Route("/{clientUnit}", name="condominium_client_invoices")
     * @Template("CondominiumManagementBundle:Resident:list_invoices.html.twig")
     *
     * @param Request     $request
     * @param Condominium $condominium
     * @param ClientUnit  $clientUnit
     *
     * @return array
     */
    public function listInvoicesAction(
        Request $request,
        Condominium $condominium,
        ClientUnit $clientUnit
    ) {
        $form = $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumIncomeFilterType'
        );
        $form->handleRequest($request);

        $query = $request->query->get(self::CONDOMINIUM_INVOICE_FILTER);
        $category = $form['category']->getData();
        $showBy = $query['showBy'];
        $startDate = $query['startDate'];
        $endDate = $query['endDate'];

        $invoices = $this->getIncomeRepository()
            ->findIncomeByCondominiumStatusCategoryAndDate(
                $condominium,
                intval($showBy),
                $category,
                $startDate,
                $endDate,
                $clientUnit
            )
            ->getQuery()
            ->getResult();
        $invoicePagination = $this->createPagination(
            $invoices,
            $request
        );

        return $this->getResponseParameters([
            'invoices' => $invoicePagination,
            'condominium' => $condominium,
            'clientUnit' => $clientUnit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Create a new invoice for client.
     *
     * @Route("/{clientUnit}/add/invoice", name="condominium_client_invoice_new")
     * @Method({"POST", "GET"})
     * @Template("CondominiumManagementBundle:Resident:new_invoice.html.twig")
     *
     * @param Request     $request
     * @param Condominium $condominium
     * @param ClientUnit  $clientUnit
     *
     * @return RedirectResponse
     */
    public function addInvoiceAction(
        Request $request,
        Condominium $condominium,
        ClientUnit $clientUnit,
        Unit $unit
    ) {
        $this->assertCanAccessCondominium($condominium);
        $invoice = new Income();

        $form = $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumIncomeInvoiceType',
            $invoice,
            [
                'condominium' => $condominium,
            ]
        );
        $form->handleRequest($request);

        $currencies = $this->getCurrencyRepository()
            ->findAllCurrencies()
            ->getQuery()
            ->getResult();

        if (!$form->isValid() || $condominium->getExchangeSetting() === null) {
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
                    'clientUnit' => $clientUnit,
                    'invoice' => $invoice,
                    'form' => $form->createView(),
                    'currencies' => $currencies,
                ]
            );
        }

        $this->invoiceData($clientUnit, $condominium, $invoice);

        return $this->redirectToRoute(
            'condominium_client_invoices',
            [
                'condominium' => $condominium->getId(),
                'clientUnit' => $clientUnit->getId(),
            ]
        );
    }

    /**
     * Edit a invoice for client.
     *
     * @Route("/{clientUnit}/edit/invoice/{invoice}", name="condominium_client_invoice_edit")
     * @Method({"POST", "GET"})
     * @Template("CondominiumManagementBundle:Resident:edit_invoice.html.twig")
     *
     * @param Request     $request
     * @param Condominium $condominium
     * @param Income      $invoice
     * @param ClientUnit  $clientUnit
     *
     * @return RedirectResponse
     */
    public function editInvoiceAction(
        Request $request,
        Condominium $condominium,
        ClientUnit $clientUnit,
        Income $invoice
    ) {
        $this->assertCanAccessCondominium($condominium);
        $form = $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumIncomeInvoiceType',
            $invoice,
            [
                'condominium' => $condominium,
            ]
        );

        $form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->getResponseParameters(
                [
                    'condominium' => $condominium,
                    'invoice' => $invoice,
                    'clientUnit' => $clientUnit,
                    'form' => $form->createView(),
                ]
            );
        }

        $this->invoiceData($clientUnit, $condominium, $invoice);

        return $this->redirectToRoute(
            'condominium_client_invoices',
            [
                'condominium' => $condominium->getId(),
                'clientUnit' => $clientUnit->getId(),
            ]
        );
    }

    /**
     * Delete a invoice from client.
     *
     * @Route(
     *  "/{clientUnit}/delete/{invoice}",
     *  name="condominium_client_invoice_delete"
     * )
     * @Method("GET")
     *
     * @param Condominium $condominium
     * @param Income      $invoice
     * @param ClientUnit  $clientUnit
     *
     * @return RedirectResponse
     */
    public function deleteInvoiceAction(
        Condominium $condominium,
        Income $invoice,
        ClientUnit $clientUnit
    ) {
        $this->assertCanAccessCondominium($condominium, $invoice);

        $this->removeAndFlush($invoice);

        return $this->redirectToRoute(
            'condominium_client_invoices',
            [
                'condominium' => $condominium->getId(),
                'clientUnit' => $clientUnit->getId(),
            ]
        );
    }

    private function invoiceData(
        $clientUnit,
        $condominium,
        $invoice,
        $request = null
    ) {
        $vat = $invoice->getVat();
        if ($invoice->getVat() === null) {
            $vat = $clientUnit->getVat();
        }
        $rate = $this->getRate($clientUnit);
        $grandTotal = $invoice->getGrandTotal();
        $subTotal = $grandTotal / (1 + ($vat / 100));
        $usdAmount = $grandTotal / $rate;
        $invoice->setUsdAmount($usdAmount);
        $invoice->setVat($vat);
        $invoice->setGrandTotal($grandTotal);
        $invoice->setSubTotal($subTotal);
        $invoice->setCondominium($condominium);
        $invoice->setClient($clientUnit);
        $invoice->setCurrency($clientUnit->getCurrency());
        if ($condominium->getExchangeSetting() !== null) {
            $invoice->setExchangeSetting($condominium->getExchangeSetting());
        }

        if ($request !== null) {
            $invoice->setTitle(
                'Invoice for rental unit #'.
                $clientUnit->getUnit()->getId()
            );
            $rentalPrice = $clientUnit->getRentalPrice();
            $vat = $clientUnit->getVat();
            $grandTotal = $rentalPrice + (($vat * $rentalPrice) / 100);
            $invoice->setGrandTotal($grandTotal);
            $invoice->setVat($vat);
            $invoice->setSubTotal($rentalPrice);

            $this->persistAndFlush($invoice);

            return $this->generateInvoice($invoice, $request, $clientUnit);
        }

        $this->persistAndFlush($invoice);
    }

    private function generateInvoice(
        $invoice,
        $request,
        $clientUnit
    ) {
        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView(
                'CondominiumManagementBundle:Partial:invoice.html.twig',
                [
                    'invoice' => $invoice,
                    'base_dir' => $this->get('kernel')->getRootDir().'/../web'.
                    $request->getBasePath(),
                ]
            ),
            dirname($this->container->getParameter('kernel.root_dir')).
            '/web/bundles/condominiummanagement/invoice/invoice'.$invoice->getId().'.pdf'
        );
        if ($clientUnit->generatedInvoice() &&
                !$clientUnit->isSendInvoice()
        ) {
            return;
        }

        return $this->sendInvoiceToClient($clientUnit, $invoice);
    }

    private function sendInvoiceToClient($clientUnit, $invoice)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject(
                $this->get('translator')->trans('condo.client.invoice.send.email.subject').' '.
                $clientUnit->getUnit()->getCondominium()->getName().' - '.
                $clientUnit->getUnit()->getId()
            )
            ->setFrom(
                $this->container->getParameter('mailer_user')
            )
            ->setTo($clientUnit->getUser()->getEmail())
            ->setBody($this->setBodyEmail($clientUnit), 'text/html')
            ->attach(
                \Swift_Attachment::fromPath(
                    dirname($this->container->getParameter('kernel.root_dir')).
                    '/web/bundles/condominiummanagement/invoice/invoice'.$invoice->getId().'.pdf'
                )
            );

        if ($this->get('mailer')->send($message)) {
            return true;
        }

        return false;
    }

    private function setBodyEmail($clientUnit)
    {
        $body = '<p>'.
                $this->get('translator')->trans('condo.client.invoice.send.email.label.dear').' '.
                ucfirst($clientUnit->getUser()->getName()).','.
            '</p><p>'.
                $this->get('translator')->trans('condo.client.invoice.send.email.text.content').
            '</p><p>'.
                $this->get('translator')->trans('condo.client.invoice.send.email.thanks').','.
            '</p>';

        return $body;
    }

    /**
     * Calculation grand total.
     *
     * @param $vat
     * @param $subTotal
     * @param $payBy
     *
     * @return int
     */
    private function getCalculateGrandTotal(
        $clientUnit
    ) {
        $vat = $clientUnit->getVat();
        $subTotal = $clientUnit->getPrice();
        $payBy = $clientUnit->getPaymentMethod();

        if ($payBy === self::DAY) {
            $startDate = $clientUnit->getStartDate();
            $endDate = $clientUnit->getEndDate();
            $days = $endDate->diff($startDate)->format('%a');

            return ($subTotal * $days) + ((($subTotal * $days) * $vat) / 100);
        }

        if ($payBy === self::MONTH) {
            return $subTotal + (($subTotal * $vat) / 100);
        }
    }

    /**
     * Get clients and pass to console command.
     *
     * @param string $day
     * @param string $hour
     *
     * @return array
     */
    public function getClients(
        $day,
        $hour
    ) {
        $clients = $this->getClientUnitRepository()
            ->findClientByDayAndHour($day, $hour)
            ->getQuery()
            ->getResult();

        return $clients;
    }

    /**
     * Create invoice.
     *
     * @param ClientUnit $client
     * @param Condominium
     *
     * @return bool
     */
    public function createInvoiceCommand(
        ClientUnit $client,
        Condominium $condominium,
        Request $request
    ) {
        $invoice = new Income();

        return $this->invoiceData(
            $client,
            $condominium,
            $invoice,
            $request
        );
    }

    /**
     * Mark invoice as paid.
     *
     * @Route(
     *  "client/{clientUnit}/paid/{invoice}",
     *  name="condominium_invoice_mark_as_paid"
     * )
     * @Method("POST")
     *
     * @param Condominium $condominium
     * @param Income      $invoice
     * @param ClientUnit  $clientUnit
     *
     * @return RedirectResponse
     */
    public function markAsPaidAction(
        Condominium $condominium,
        Income $invoice,
        ClientUnit $clientUnit
    ) {
        $this->assertCanAccessCondominium($condominium, $invoice);

        if ($invoice->getStatus() === InvoiceStatus::UNPAID) {
            $invoice->setStatus(InvoiceStatus::PAID);
            $invoice->setMarkAsPaidBy($this->getUser());
            $invoice->setPaymentDate(new DateTime());
            $this->persistAndFlush($invoice);
        }

        return $this->redirectToRoute(
            'condominium_client_invoices',
            [
                'condominium' => $condominium->getId(),
                'clientUnit' => $clientUnit->getId(),
            ]
        );
    }

    /**
     * Rollback invoice client.
     *
     * @Route(
     *  "client/{clientUnit}/rollback/{invoice}",
     *  name="condominium_invoice_rollback"
     * )
     *
     * @param Condominium $condominium
     * @param Income      $invoice
     * @param ClientUnit  $clientUnit
     *
     * @return RedirectResponse
     */
    public function rollbackInvoiceClientAction(
        Condominium $condominium,
        Income $invoice,
        ClientUnit $clientUnit
    ) {
        $this->assertCanAccessCondominium($condominium, $invoice);

        if ($invoice->getStatus() === InvoiceStatus::PAID) {
            $invoice->setStatus(InvoiceStatus::UNPAID);
            $this->persistAndFlush($invoice);
        }

        return $this->redirectToRoute(
            'condominium_client_invoices',
            [
                'condominium' => $condominium->getId(),
                'clientUnit' => $clientUnit->getId(),
            ]
        );
    }

    /**
     * Show a invoice for client.
     *
     * @Route("client/{clientUnit}/show/{invoice}", name="condominium_invoice_show")
     * @Method("GET")
     * @Template("CondominiumManagementBundle:Resident:show_invoice.html.twig")
     *
     * @param Condominium $condominium
     * @param Income      $invoice
     * @param ClientUnit  $client
     *
     * @return array
     */
    public function showAction(
        Condominium $condominium,
        Income $invoice,
        ClientUnit $clientUnit
    ) {
        $this->assertCanAccessCondominium($condominium);

        $currencies = $this->getCurrencyRepository()
            ->findAllCurrencies()
            ->getQuery()
            ->getResult();

        return $this->getResponseParameters(
            [
                'condominium' => $condominium,
                'invoice' => $invoice,
                'currencies' => $currencies,
                'clientUnit' => $clientUnit,
            ]
        );
    }

    /**
     * Gets Unpaid Grand Total from each client.
     *
     * @Template("CondominiumManagementBundle:Resident:unpaid.html.twig")
     *
     * @param Condominium     $condominium
     * @param ClientUnit      $clientUnit
     * @param ExchangeSetting $exchangeSetting
     *
     * @return array
     */
    public function getUnpaidAction(
        Condominium $condominium,
        ClientUnit $clientUnit
    ) {
        $unpaids = $this->getIncomeRepository()
            ->findUnpaidByCondoAndClient($condominium, $clientUnit);

        return $this->getResponseParameters(
            [
                'unpaids' => $unpaids,
                'rate' => $this->getRate($clientUnit),
            ]
        );
    }

    private function getRate($clientUnit)
    {
        $rate = 1;
        if ($clientUnit->getExchangeSetting() !== null) {
            $currency = $clientUnit->getCurrency();
            $rate = $clientUnit->getExchangeSetting()
                ->getValue()[$currency->getId()];
        }

        return $rate;
    }

    /**
     * @Route(
     *  "/edit/unit/price/{clientUnit}",
     *  name="condominium_client_edit_unit_price"
     * )
     * @Method("POST")
     *
     * @param ClientUnit $clientUnit
     */
    public function editUnitPriceAction(
        Request $request,
        Condominium $condominium,
        ClientUnit $clientUnit
    ) {
        $unitPrice = $request->request->get('unitPrice');
        $clientUnit->setUnitPrice($unitPrice);

        $this->persistAndFlush($clientUnit);

        return $this->redirectToRoute(
            'condominium_residents_list',
            ['condominium' => $condominium->getId()]
        );
    }
}
