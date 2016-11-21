<?php

namespace WeBridge\UserBundle\Controller;

use WeBridge\UserBundle\Entity\User;
use CondoBundle\Traits\HasControllerUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserProfileController extends Controller
{
    use HasControllerUtils;
    /**
     * Displays a form to edit an user Entity.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createCreateForm();

        $form->handleRequest($request);

        return $this->render('WeBridgeUserBundle:Profile:edit.html.twig',
            [
                'edit_form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * Update user profile.
     *
     * @Route("update", name="update_profile")
     *
     * @param Reqest $request
     */
    public function updateAction(Request $request)
    {
        $form = $this->createCreateForm();

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $user = $form->getData();

            $this->persistAndFlush($user);

            return $this->redirectToRoute('fos_user_profile_show');
        }
    }

    public function createCreateForm()
    {
        $user = $this->getUser();

        return $this->createForm(
            'WeBridge\UserBundle\Form\UserFormType',
            $user,
            [
                'action' => $this->generateUrl('update_profile'),
            ]
        );
    }
}
