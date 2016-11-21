<?php

namespace RealtyCompanyBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use RealtyCompanyBundle\Traits\HasRealtyControllerUtils;
use RealtyCompanyBundle\Entity\RealtyCompany;
use WeBridge\UserBundle\Entity\User;

class RealtyCompanyController extends Controller
{
    use HasRealtyControllerUtils;

    /**
     * Finds and displays a Realty Managers.
     *
     * @Route("/{realtyCompany}/managers", name="realty_managers_show")
     * @Method("GET")
     * @Template("RealtyCompanyBundle:RealtyCompany:show.html.twig")
     *
     * @param RealtyCompany $realtyCompany
     *
     * @return array
     */
    public function showAction(RealtyCompany $realtyCompany)
    {
        $this->assertCanAccessRealtyCompany($realtyCompany);

        return $this->getResponseParameters(
            [
                'realtyCompany' => $realtyCompany,
            ]
        );
    }

    /**
     * @Route("/{realtyCompany}/managers", name="realty_managers_new")
     * @Method("POST")
     *
     * @param Request       $request
     * @param RealtyCompany $realtyCompany
     *
     * @return RedirectResponse
     */
    public function addNewMember(Request $request, RealtyCompany $realtyCompany)
    {
        $this->assertCanAccessRealtyCompany($realtyCompany);
        $managerUsername = $request->get('username');
        /* @var User $member */
        $manager = $this->getUserRepository()
            ->findOneUserByPhoneOrEmail($managerUsername);

        if (empty($manager)) {
            return $this->redirectToRoute(
                'realty_managers_show',
                [
                    'realtyCompany' => $realtyCompany->getId(),
                ]
            );
        }

        $realtyCompany->addManager($manager);

        $this->persistAndFlush($realtyCompany);
            // Checks memberships AFTER ADD too add/remove service role if needed.
        $this->getUserRepository()
            ->updateRealtyCompanyRoleForUser(
                $manager,
                $this->get('security.token_storage')
            );

        return $this->redirectToRoute(
            'realty_managers_show',
            [
                'realtyCompany' => $realtyCompany->getId(),
            ]
        );
    }

    /**
     * @Route("/{realtyCompany}/managers/{manager}", name="realty_managers_delete")
     * @Method("DELETE")
     *
     * @param RealtyCompany $realtyCompany
     * @param User          $manager
     *
     * @return RedirectResponse
     */
    public function deleteManager(RealtyCompany $realtyCompany, User $manager)
    {
        $this->assertCanAccessRealtyCompany($realtyCompany);
        $user = $this->getUser();

        if ($manager === $user || $manager->hasRole('ROLE_ADMIN')) {
            return $this->redirectToRoute(
                'realty_managers_show',
                [
                    'realtyCompany' => $realtyCompany->getId(),
                ]
            );
        }

        $realtyCompany->removeManager($manager);

        $this->persistAndFlush($realtyCompany);
            // Checks memberships AFTER DELETE too add/remove service role if needed.
        $this->getUserRepository()
            ->updateRealtyCompanyRoleForUser(
                $manager,
                $this->get('security.token_storage')
            );

        return $this->redirectToRoute(
            'realty_managers_show',
            [
                'realtyCompany' => $realtyCompany->getId(),
            ]
        );
    }
}
