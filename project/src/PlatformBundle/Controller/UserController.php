<?php

namespace PlatformBundle\Controller;

use CondoBundle\Traits\HasControllerUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use WeBridge\UserBundle\Entity\User;
use WeBridge\UserBundle\Entity\RoleType;
use Symfony\Component\HttpFoundation\Response;
use CondoBundle\Entity\DatabaseFile;
use CondoBundle\Constant\RoleTypes;

/**
 * @Route("/users")
 */
class UserController extends Controller
{
    use HasControllerUtils;

    /**
     * @Route(
     *     path="/",
     *     methods={"GET"},
     *     name="platform_user_list"
     * )
     * @Template()
     */
    public function indexAction()
    {
        /** @var User[] $users */
        $users = $this->getUserRepository()
            ->findAll();

        return [
            'users' => $users,
        ];
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/delete/{user}/", name="platform_user_delete")
     *
     * @param User $user
     *
     * @return RedirectResponse
     */
    public function deleteAction(
        User $user
    ) {
        try {
            $this->removeAndFlush($user);

            return $this->redirectToRoute(
                'platform_user_list'
            );
        } catch (\Exception $e) {
            $this->addFlash(
                'error',
                $this->get('translator')
                    ->trans('error.message.can.not.delete.user')
            );
        }

        return $this->redirectToRoute(
            'platform_user_list'
        );
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/new", name="platform_user_new")
     * @Method({"GET", "POST"})
     * @Template("PlatformBundle:User:new.html.twig")
     *
     * @param Request $request
     *
     * @return array|RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $roleTypes = $this->getRoleTypeRepository()->findAll();
        $user = new User();
        $form = $this->createForm(
            'PlatformBundle\Form\UserType',
            $user
        );
        $form->handleRequest($request);

        $returnAddForm = [
            'form' => $form->createView(),
            'roleTypes' => $roleTypes,
        ];

        if (!$form->isSubmitted() && !$form->isValid()) {
            return $returnAddForm;
        }

        $roles = [];
        $roles = $request->request->get('module');
        $spaceType = $request->request->get('spaceType');
        $email = $form->get('email')->getData();
        $picture = $form->get('picture')->getData();

        if (empty($roles)) {
            $this->addFlash(
                'error',
                $this->get('translator')
                    ->trans('error.message.please.check.modules')
            );

            return $returnAddForm;
        }

        //Checking user exist created
        $userExistRegister = $this->getUserRepository()->findUserByEmail($email);
        if (!empty($userExistRegister)) {
            $this->addFlash(
                'error',
                $this->get('translator')
                    ->trans('error.message.user.exist.created')
            );

            return $returnAddForm;
        }

        if ($picture !== null) {
            $databasefile = new DatabaseFile($picture);
            $this->persistAndFlush($databasefile);
            $user->setPicture($databasefile);
        }

        $user->setRoles($roles);
        $user->setUsername($email);
        $user->setEnabled(true);
        $user->setSpaceType($spaceType);
        $this->persistAndFlush($user);

        return $this->redirectToRoute(
            'platform_user_list'
        );
    }

    /**
     * Edit user entity.
     *
     * @Route("/edit/{user}", name="platform_user_edit")
     * @Method({"GET", "POST"})
     * @Template("PlatformBundle:User:edit.html.twig")
     *
     * @param Request $request
     * @param User    $user
     *
     * @return array|RedirectResponse|Response
     */
    public function editAction(
        Request $request,
        User $user
    ) {
        $userRoleTypes = $this->getUserRoleType($user->getRoles());
        $form = $this->createForm(
            'PlatformBundle\Form\UserType',
            $user
        );
        $form->handleRequest($request);

        $returnEditForm = [
            'form' => $form->createView(),
            'roleTypes' => $this->getRoleTypes(),
            'userRoleTypes' => $userRoleTypes,
            'user' => $user,
        ];

        if (!$form->isSubmitted() && !$form->isValid()) {
            return $returnEditForm;
        }

        $roles = [];
        $roles = $request->request->get('module');
        $picture = $form->get('picture')->getData();
        $spaceType = $request->request->get('spaceType');

        if (empty($roles)) {
            $this->addFlash(
                'error',
                $this->get('translator')
                    ->trans('error.message.please.check.module')
            );

            return $returnEditForm;
        }

        if ($picture !== null) {
            $databasefile = new DatabaseFile($picture);
            $this->persistAndFlush($databasefile);
            $user->setPicture($databasefile);
        }
        $email = $user->getUsername();
        $user->setEmail($email);
        $user->setEmailCanonical($email);
        $user->setRoles($roles);
        $user->setSpaceType($spaceType);
        $this->persistAndFlush($user);

        return $this->redirectToRoute(
            'platform_user_list'
        );
    }

    private function getUserRoleType($userRoleTypes)
    {
        $roleType = $this->getUserModuleRepository()
            ->findUserRoleTypeBy($userRoleTypes);
        if (!empty($roleType)) {
            return $roleType;
        }

        return null;
    }

    private function getRoleTypes()
    {
        return $this->getRoleTypeRepository()->findAll();
    }

    /**
     * @Route(
     *  "/get/space/module/{roleType}/{user}",
     *  name="list_space_and_modules"
     * )
     * @Template("PlatformBundle:User:list.space.module.html.twig")
     *
     * @param RoleType $roleType
     * @param User     $user
     *
     * @return array
     */
    public function getSpaceAndModuleAction(
        RoleType $roleType = null,
        User $user = null
    ) {
        $roleTypeSpaces = [];
        if ($roleType->getName() === RoleTypes::CONDOMINIUM) {
            $roleTypeSpaces = $this->getCondominiumRepository()->findAll();
        }

        if ($roleType->getName() == RoleTypes::SERVICE) {
            $roleTypeSpaces = $this->getServiceRepository()->findAll();
        }

        $modules = $this->getUserModuleRepository()
            ->findAllModulesByRoleType($roleType);

        if ($user !== null) {
            return [
                'modules' => $modules,
                'roleTypeSpaces' => $roleTypeSpaces,
                'user' => $user,
            ];
        }

        return [
            'modules' => $modules,
            'roleTypeSpaces' => $roleTypeSpaces,
        ];
    }

    /**
     * @Route("/show/{user}", name="platform_user_show")
     * @Template("PlatformBundle:User:show.html.twig")
     *
     * @param User $user
     */
    public function showAction(User $user)
    {
        $userRoleTypes = $this->getUserRoleType($user->getRoles());

        return [
            'user' => $user,
            'roleTypes' => $this->getRoleTypes(),
            'userRoleTypes' => $userRoleTypes,
        ];
    }
}
