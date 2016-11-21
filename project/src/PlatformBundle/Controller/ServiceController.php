<?php

namespace PlatformBundle\Controller;

use CondoBundle\Traits\HasPagination;
use WeBridge\UserBundle\Entity\User;
use CondoBundle\Entity\Service;
use CondoBundle\Traits\HasControllerUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Service controller.
 *
 * @Route("/services")
 */
class ServiceController extends Controller
{
    use HasControllerUtils;
    use HasPagination;

    /**
     * Lists all Service entities.
     *
     * @Route("/", name="platform_service_list")
     * @Template("PlatformBundle:service:index.html.twig")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $services = $em->getRepository('CondoBundle:Service')->findAll();

        $servicesPagination = $this->createPagination(
            $services,
            $request
        );

        return [
            'services' => $servicesPagination,
        ];
    }

    /**
     * Creates a new Service entity.
     *
     * @Route("/new", name="platform_service_new")
     * @Method({"GET", "POST"})
     * @Template("PlatformBundle:service:new.html.twig")
     *
     * @param Request $request
     *
     * @return array|RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $service = new Service();
        $form = $this->createForm('PlatformBundle\Form\ServiceType', $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($service);
            $em->flush();

            return $this->redirectToRoute(
                'platform_service_show',
                ['id' => $service->getId()]
            );
        }

        return [
            'service' => $service,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Service entity.
     *
     * @Route("/{id}", name="platform_service_show")
     * @Method("GET")
     * @Template("PlatformBundle:service:show.html.twig")
     *
     * @param Service $service
     *
     * @return array|Response
     */
    public function showAction(Service $service)
    {
        $deleteForm = $this->createDeleteForm($service);

        return [
            'service' => $service,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Service entity.
     *
     * @Route("/{id}/edit", name="platform_service_edit")
     * @Method({"GET", "POST"})
     * @Template("PlatformBundle:service:edit.html.twig")
     *
     * @param Request $request
     * @param Service $service
     *
     * @return array|RedirectResponse|Response
     */
    public function editAction(Request $request, Service $service)
    {
        $deleteForm = $this->createDeleteForm($service);
        $editForm = $this->createForm('PlatformBundle\Form\ServiceType', $service);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($service);
            $em->flush();

            return $this->redirectToRoute(
                'platform_service_edit',
                ['id' => $service->getId()]
            );
        }

        return [
            'service' => $service,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a Service entity.
     *
     * @Route("/{id}", name="platform_service_delete")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param Service $service
     *
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, Service $service)
    {
        $form = $this->createDeleteForm($service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($service);
            $em->flush();
        }

        return $this->redirectToRoute('platform_service_list');
    }

    /**
     * @Route("/{id}/managers", name="platform_service_managers_new")
     * @Method("POST")
     *
     * @param Request $request
     * @param Service $service
     *
     * @return RedirectResponse
     */
    public function addNewMember(Request $request, Service $service)
    {
        $memberUsername = $request->get('username');

        /** @var User $member */
        $member = $this->getUserRepository()
            ->findOneUserByPhoneOrEmail($memberUsername);

        if (!empty($member)) {
            $service->addManager($member);
            $this->persistAndFlush($service);

            // Checks memberships AFTER ADD too add/remove service role if needed.
            $this->getUserRepository()
                ->updateServiceRoleForUser(
                    $member,
                    $this->get('security.token_storage')
                );
        }

        return $this->redirectToRoute(
            'platform_service_show',
            [
                'id' => $service->getId(),
            ]
        );
    }

    /**
     * @Route("/{id}/managers/{manager}", name="platform_service_managers_delete")
     * @Method("DELETE")
     *
     * @param Service $service
     * @param User    $manager
     *
     * @return RedirectResponse
     */
    public function deleteMember(Service $service, User $manager)
    {
        $service->removeManager($manager);
        $this->persistAndFlush($service);

        // Checks memberships AFTER DELETE too add/remove service role if needed.
        $this->getUserRepository()
            ->updateServiceRoleForUser(
                $manager,
                $this->get('security.token_storage')
            );

        return $this->redirectToRoute(
            'platform_service_show',
            [
                'id' => $service->getId(),
            ]
        );
    }

    /**
     * Creates a form to delete a Service entity.
     *
     * @param Service $service The Service entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Service $service)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'platform_service_delete',
                    ['id' => $service->getId()]
                )
            )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
