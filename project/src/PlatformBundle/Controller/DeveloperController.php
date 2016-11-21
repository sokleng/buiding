<?php

namespace PlatformBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DeveloperBundle\Entity\Developer;
use CondoBundle\Traits\HasControllerUtils;
use WeBridge\UserBundle\Entity\User;

/**
 * Developer controller.
 *
 * @Route("/developers")
 */
class DeveloperController extends Controller
{
    use HasControllerUtils;

    /**
     * Lists all developer entities.
     *
     * @Route("/", name="platform_developer_list")
     * @Template("PlatformBundle:Developer:index.html.twig")
     * @Method("GET")
     *
     * @return array
     */
    public function indexAction()
    {
        $developers = $this->getDeveloperRepository()->findAll();

        return [
            'developers' => $developers,
        ];
    }

    /**
     * Creates a new Developer entity.
     *
     * @Route("/new", name="platform_developers_new")
     * @Method({"GET", "POST"})
     * @Template("PlatformBundle:Developer:new.html.twig")
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function newAction(Request $request)
    {
        $developer = new Developer();
        $form = $this->createForm(
            'PlatformBundle\Form\DeveloperType',
            $developer
        );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($developer);

            return $this->redirectToRoute(
                'platform_developer_show',
                ['developer' => $developer->getId()]
            );
        }

        return [
            'developer' => $developer,
            'form' => $form->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Developer entity.
     *
     * @Route("/{developer}/edit", name="platform_developer_edit")
     * @Method({"GET", "POST"})
     * @Template("PlatformBundle:Developer:edit.html.twig")
     *
     * @param Request   $request
     * @param Developer $developer
     *
     * @return array|RedirectResponse
     */
    public function editAction(
        Request $request,
        Developer $developer
    ) {
        $deleteForm = $this->createDeleteForm($developer);
        $editForm = $this->createForm(
            'PlatformBundle\Form\DeveloperType',
            $developer
        );
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->persistAndFlush($developer);

            return $this->redirectToRoute(
                'platform_developer_edit',
                ['developer' => $developer->getId()]
            );
        }

        return [
            'developer' => $developer,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Finds and displays a Developer entity.
     *
     * @Route("/{developer}", name="platform_developer_show")
     * @Method("GET")
     * @Template("PlatformBundle:Developer:show.html.twig")
     *
     * @param Developer $developer
     *
     * @return array
     */
    public function showAction(Developer $developer)
    {
        $deleteForm = $this->createDeleteForm($developer);

        return [
            'developer' => $developer,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a developer entity.
     *
     * @Route("/{developer}", name="platform_developer_delete")
     * @Method("DELETE")
     *
     * @param Request   $request
     * @param Developer $developer
     *
     * @return RedirectResponse
     */
    public function deleteAction(
        Request $request,
        Developer $developer
    ) {
        $form = $this->createDeleteForm($developer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->removeAndFlush($developer);
        }

        return $this->redirectToRoute('platform_developer_list');
    }

    /**
     * Creates a form to delete a developer entity.
     *
     * @param Developer $developer The developer entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Developer $developer)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'platform_developer_delete',
                    ['developer' => $developer->getId()]
                )
            )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @Route("/{developer}/managers", name="platform_developer_managers_new")
     * @Method("POST")
     *
     * @param Request   $request
     * @param Developer $developer
     *
     * @return RedirectResponse
     */
    public function addNewMember(
        Request $request,
        Developer $developer
    ) {
        $memberUsername = $request->get('username');

        /** @var User $member */
        $member = $this->getUserRepository()
            ->findOneUserByPhoneOrEmail($memberUsername);

        if (!empty($member)) {
            $developer->addManager($member);
            $this->persistAndFlush($developer);

            // Checks memberships AFTER ADD too add/remove service role if needed.
            $this->getUserRepository()
                ->updateDeveloperRoleForUser(
                    $member,
                    $this->get('security.token_storage')
                );
        }

        return $this->redirectToRoute(
            'platform_developer_show',
            [
                'developer' => $developer->getId(),
            ]
        );
    }

    /**
     * @Route("/{developer}/managers/{manager}", name="platform_developer_managers_delete")
     * @Method("DELETE")
     *
     * @param Developer $developer
     * @param User      $manager
     *
     * @return RedirectResponse
     */
    public function deleteMember(
        Developer $developer,
        User $manager
    ) {
        $developer->removeManager($manager);
        $this->persistAndFlush($developer);

        // Checks memberships AFTER DELETE too add/remove service role if needed.
        $this->getUserRepository()
            ->updateServiceRoleForUser(
                $manager,
                $this->get('security.token_storage')
            );

        return $this->redirectToRoute(
            'platform_developer_show',
            [
                'developer' => $developer->getId(),
            ]
        );
    }
}
