<?php

namespace PlatformBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use CondoBundle\Traits\HasControllerUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use WeBridge\UserBundle\Entity\User;
use CondoBundle\Entity\Condominium;
use CondoBundle\Entity\Currency;
use Symfony\Component\Form\Form;

/**
 * Condominium controller.
 *
 * @Route("/condominiums")
 */
class CondominiumController extends Controller
{
    use HasControllerUtils;

    const USD = 'USD';

    /**
     * Lists all Condominium entities.
     *
     * @Route("/", name="platform_condominium_list")
     * @Template("PlatformBundle:condominium:index.html.twig")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm('PlatformBundle\Form\SwitchCurrencyType');
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $this->changeCurrencyAction($form);
        }

        $condos = $em->getRepository('CondoBundle:Condominium')->findAll();

        return [
            'condos' => $condos,
            'form' => $form->createView(),
        ];
    }

    /**
     * Creates a new Condominium entity.
     *
     * @Route("/new", name="platform_condominium_new")
     * @Method({"GET", "POST"})
     * @Template("PlatformBundle:condominium:new.html.twig")
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response|array
     */
    public function newAction(Request $request)
    {
        $condominium = new Condominium();
        $form = $this->createForm(
            'PlatformBundle\Form\CondominiumType',
            $condominium
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($condominium);
            $em->flush();

            return $this->redirectToRoute(
                'platform_condominium_show',
                array('id' => $condominium->getId())
            );
        }

        return [
            'condominium' => $condominium,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Condominium entity.
     *
     * @Route("/{id}", name="platform_condominium_show")
     * @Method("GET")
     * @Template("PlatformBundle:condominium:show.html.twig")
     *
     * @param Condominium $condominium
     *
     * @return Response|array
     */
    public function showAction(Condominium $condominium)
    {
        $deleteForm = $this->createDeleteForm($condominium);

        return [
            'condominium' => $condominium,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Condominium entity.
     *
     * @Route("/{id}/edit", name="platform_condominium_edit")
     * @Method({"GET", "POST"})
     * @Template("PlatformBundle:condominium:edit.html.twig")
     *
     * @param Request     $request
     * @param Condominium $condominium
     *
     * @return RedirectResponse|Response|array
     */
    public function editAction(Request $request, Condominium $condominium)
    {
        $deleteForm = $this->createDeleteForm($condominium);
        $editForm = $this->createForm(
            'PlatformBundle\Form\CondominiumType',
            $condominium
        );
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($condominium);
            $em->flush();

            return $this->redirectToRoute(
                'platform_condominium_edit',
                ['id' => $condominium->getId()]
            );
        }

        return [
            'condominium' => $condominium,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a Condominium entity.
     *
     * @Route("/{id}", name="platform_condominium_delete")
     * @Method("DELETE")
     *
     * @param Request     $request
     * @param Condominium $condominium
     *
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Condominium $condominium)
    {
        $form = $this->createDeleteForm($condominium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($condominium);
            $em->flush();
        }

        return $this->redirectToRoute('platform_condominium_list');
    }

    /**
     * Creates a form to delete a Condominium entity.
     *
     * @param Condominium $condominium The Condominium entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Condominium $condominium)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'platform_condominium_delete',
                    ['id' => $condominium->getId()]
                )
            )
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @Route("/{id}/managers", name="platform_condominium_managers_new")
     * @Method("POST")
     *
     * @param Request     $request
     * @param Condominium $condominium
     *
     * @return RedirectResponse
     */
    public function addNewMember(Request $request, Condominium $condominium)
    {
        $memberUsername = $request->get('username');

        /** @var User $manager */
        $manager = $this->getUserRepository()
            ->findOneUserByPhoneOrEmail($memberUsername);

        if (!empty($manager)) {
            $condominium->addManager($manager);
            $this->persistAndFlush($condominium);

            // Checks memberships AFTER ADD too add/remove service role if needed.
            $this->getUserRepository()
                ->updateCondominiumRoleForUser(
                    $manager,
                    $this->get('security.token_storage')
                );
        }

        return $this->redirectToRoute(
            'platform_condominium_show',
            [
                'id' => $condominium->getId(),
            ]
        );
    }

    /**
     * @Route("/{id}/managers/{manager}", name="platform_condominium_managers_delete")
     * @Method("DELETE")
     *
     * @param Condominium $condominium
     * @param User        $manager
     *
     * @return RedirectResponse
     */
    public function deleteMember(Condominium $condominium, User $manager)
    {
        $condominium->removeManager($manager);
        $this->persistAndFlush($condominium);

        // Checks memberships AFTER DELETE too add/remove service role if needed.
        $this->getUserRepository()
            ->updateCondominiumRoleForUser(
                $manager,
                $this->get('security.token_storage')
            );

        return $this->redirectToRoute(
            'platform_condominium_show',
            [
                'id' => $condominium->getId(),
            ]
        );
    }

    /**
     * Change the currency of condominium.
     *
     * @param Form $form
     *
     * @return RedirectResponse
     */
    private function changeCurrencyAction(Form $form)
    {
        $currency = $form['currency']->getData();
        $condominiumId = $form['condominiumId']->getData();
        $condominium = $this->getCondominiumRepository()->find($condominiumId);
        if ($condominium->getCurrency() === $currency) {
            return $this->redirectToRoute('platform_condominium_list');
        }
        if ($condominium->getExchangeSetting() === null) {
            $this->addFlash(
                    'error',
                    $this->get('translator')
                        ->trans('error.message.unknown.exchange.rate')
                );

            return $this->redirectToRoute('platform_condominium_list');
        }
        $this->updatePriceOfEachUnit($condominium, $currency);
        $condominium->setCurrency($currency);
        $this->persistAndFlush($condominium);

        return $this->redirectToRoute('platform_condominium_list');
    }

    /**
     * Update price of each unit in condo base on condo.
     *
     * @param Condominium $condominium
     * @param Currency    $currency
     */
    private function updatePriceOfEachUnit(
        Condominium $condominium,
        Currency $currency
    ) {
        $oldCurrency = $condominium->getCurrency();
        $exchangeSetting = $condominium->getExchangeSetting();
        $units = $this->getUnitRepository()->findAll();
        foreach ($units as $unit) {
            $usdPrice = $unit->getPrice() / $exchangeSetting->getValue()[$oldCurrency->getId()];
            if ($currency->getCurrency() === self::USD) {
                $price = $usdPrice;
            } else {
                $price = $usdPrice * $exchangeSetting->getValue()[$currency->getId()];
            }
            $unit->setPrice($price);
            $this->persistAndFlush($unit);
        }
    }
}
