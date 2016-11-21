<?php

namespace PlatformBundle\Controller;

use CondoBundle\Traits\HasControllerUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use RealtyCompanyBundle\Entity\RealtyCompany;
use WeBridge\UserBundle\Entity\User;

/**
 * RealtyCompany controller.
 *
 * @Route("/realty-companys")
 */
class RealtyCompanyController extends Controller
{
    use HasControllerUtils;

    /**
     * Lists all RealtyCompany entities.
     *
     * @Route("/", name="realty_company_list")
     * @Method("GET")
     * @Template("PlatformBundle:realty-company:index.html.twig")
     */
    public function indexAction()
    {
        $realtyCompanies = $this->getRealtyCompanyRepository()
            ->findAllRealtyCompany()->getQuery()->getResult();

        return [
            'realtyCompanies' => $realtyCompanies,
        ];
    }

    /**
     * Creates a new Realty Company.
     *
     * @Route("/new", name="platform_realty_company_new")
     * @Method({"GET", "POST"})
     * @Template("PlatformBundle:realty-company:new.html.twig")
     *
     * @param Request $request
     *
     * @return array|RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $realtyCompany = new RealtyCompany();
        $form = $this->createForm(
            'RealtyCompanyBundle\Form\RealtyCompanyType',
            $realtyCompany
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($realtyCompany);

            return $this->redirectToRoute(
                'realty_company_list',
                ['realtyCompany' => $realtyCompany->getId()]
            );
        }

        return [
            'realtyCompany' => $realtyCompany,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Realty Company entity.
     *
     * @Route("/{realtyCompany}", name="platform_realty_company_show")
     * @Method("GET")
     * @Template("PlatformBundle:realty-company:show.html.twig")
     *
     * @param RealtyCompany $realtyCompany
     *
     * @return array|Response
     */
    public function showAction(RealtyCompany $realtyCompany)
    {
        return [
            'realtyCompany' => $realtyCompany,
        ];
    }

    /**
     * Deletes a Realty Company entity.
     *
     * @Route("/{realtyCompany}", name="platform_realty_company_delete")
     * @Method("DELETE")
     *
     * @param RealtyCompany $realtyCompany
     *
     * @return RedirectResponse
     */
    public function deleteAction(RealtyCompany $realtyCompany)
    {
        $this->removeAndFlush($realtyCompany);

        return $this->redirectToRoute('realty_company_list');
    }

    /**
     * Displays a form to edit an existing Realty Company.
     *
     * @Route("/{realtyCompany}/edit", name="platform_realty_company_edit")
     * @Method({"GET", "POST"})
     * @Template("PlatformBundle:realty-company:edit.html.twig")
     *
     * @param Request       $request
     * @param RealtyCompany $realtyCompany
     *
     * @return array|RedirectResponse
     */
    public function editAction(Request $request, RealtyCompany $realtyCompany)
    {
        $editForm = $this->createForm(
            'RealtyCompanyBundle\Form\RealtyCompanyType',
            $realtyCompany
        );
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->persistAndFlush($realtyCompany);

            return $this->redirectToRoute(
                'platform_realty_company_edit',
                ['realtyCompany' => $realtyCompany->getId()]
            );
        }

        return [
            'realtyCompany' => $realtyCompany,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * @Route("/{realtyCompany}/managers", name="platform_realty_company_managers_new")
     * @Method("POST")
     *
     * @param Request       $request
     * @param RealtyCompany $realtyCompany
     *
     * @return RedirectResponse
     */
    public function addNewMember(Request $request, RealtyCompany $realtyCompany)
    {
        $managerUsername = $request->get('username');

        /* @var User $member */
        $manager = $this->getUserRepository()
            ->findOneUserByPhoneOrEmail($managerUsername);

        if (!empty($manager)) {
            $realtyCompany->addManager($manager);
            $this->persistAndFlush($realtyCompany);

            // Checks memberships AFTER ADD too add/remove service role if needed.
            $this->getUserRepository()
                ->updateRealtyCompanyRoleForUser(
                    $manager,
                    $this->get('security.token_storage')
                );
        }

        return $this->redirectToRoute(
            'platform_realty_company_show',
            [
                'realtyCompany' => $realtyCompany->getId(),
            ]
        );
    }

    /**
     * @Route("/{realtyCompany}/managers/{manager}", name="platform_realty_company_managers_delete")
     * @Method("DELETE")
     *
     * @param RealtyCompany $realtyCompany
     * @param User          $manager
     *
     * @return RedirectResponse
     */
    public function deleteManager(RealtyCompany $realtyCompany, User $manager)
    {
        $realtyCompany->removeManager($manager);
        $this->persistAndFlush($realtyCompany);

        // Checks memberships AFTER DELETE too add/remove service role if needed.
        $this->getUserRepository()
            ->updateRealtyCompanyRoleForUser(
                $manager,
                $this->get('security.token_storage')
            );

        return $this->redirectToRoute(
            'platform_realty_company_show',
            [
                'realtyCompany' => $realtyCompany->getId(),
            ]
        );
    }
}
