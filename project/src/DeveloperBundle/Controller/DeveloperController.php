<?php

namespace DeveloperBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use DeveloperBundle\Traits\HasDeveloperControllerUtils;
use DeveloperBundle\Entity\Developer;
use WeBridge\UserBundle\Entity\User;

class DeveloperController extends Controller
{
    use HasDeveloperControllerUtils;

    /**
     * Finds and displays a Developer Managers.
     *
     * @Route("/{developer}/managers", name="developer_managers_show")
     * @Method("GET")
     * @Template("DeveloperBundle:Developer:show.html.twig")
     *
     * @param Developer $developer
     *
     * @return array
     */
    public function showAction(Developer $developer)
    {
        $this->assertCanAccessDeveloper($developer);

        return $this->getResponseParameters(
            [
                'developer' => $developer,
            ]
        );
    }

    /**
     * @Route("/{developer}/managers", name="developer_managers_new")
     * @Method("POST")
     *
     * @param Request   $request
     * @param Developer $developer
     *
     * @return RedirectResponse
     */
    public function addNewMember(Request $request, Developer $developer)
    {
        $this->assertCanAccessDeveloper($developer);
        $managerUsername = $request->get('username');
        /* @var User $member */
        $manager = $this->getUserRepository()
            ->findOneUserByPhoneOrEmail($managerUsername);

        if (!empty($manager)) {
            $developer->addManager($manager);
            $this->persistAndFlush($developer);

            // Checks memberships AFTER ADD too add/remove service role if needed.
            $this->getUserRepository()
                ->updateDeveloperRoleForUser(
                    $manager,
                    $this->get('security.token_storage')
                );
        }

        return $this->redirectToRoute(
            'developer_managers_show',
            [
                'developer' => $developer->getId(),
            ]
        );
    }

    /**
     * @Route("/{developer}/managers/{manager}", name="developer_managers_delete")
     * @Method("DELETE")
     *
     * @param Developer $developer
     * @param User      $manager
     *
     * @return RedirectResponse
     */
    public function deleteManager(Developer $developer, User $manager)
    {
        $this->assertCanAccessDeveloper($developer);
        $user = $this->getUser();

        if ($manager === $user || $manager->hasRole('ROLE_ADMIN')) {
            return $this->redirectToRoute(
                'developer_managers_show',
                [
                    'developer' => $developer->getId(),
                ]
            );
        }

        $developer->removeManager($manager);

        $this->persistAndFlush($developer);
            // Checks memberships AFTER DELETE too add/remove service role if needed.
            $this->getUserRepository()
            ->updateDeveloperRoleForUser(
                $manager,
                $this->get('security.token_storage')
            );

        return $this->redirectToRoute(
            'developer_managers_show',
            [
                'developer' => $developer->getId(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Developer entity.
     *
     * @Route("/{developer}/edit", name="developer_developer_edit")
     * @Method({"GET", "POST"})
     * @Template("DeveloperBundle:Developer:edit.html.twig")
     *
     * @param Request   $request
     * @param Developer $developer
     *
     * @return array|RedirectResponse
     */
    public function editAction(Request $request, Developer $developer)
    {
        $this->assertCanAccessDeveloper($developer);

        $editForm = $this->createForm(
            'DeveloperBundle\Form\DeveloperType',
            $developer
        );
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->persistAndFlush($developer);

            return $this->redirectToRoute(
                'developer_home'
            );
        }

        return $this->getResponseParameters([
            'developer' => $developer,
            'edit_form' => $editForm->createView(),
        ]);
    }
}
