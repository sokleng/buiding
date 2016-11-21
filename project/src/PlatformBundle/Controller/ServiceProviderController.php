<?php

namespace PlatformBundle\Controller;

use CondoBundle\Traits\HasPagination;
use CondoBundle\Traits\HasControllerUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use CondoBundle\Entity\ServiceProvider;
use Symfony\Component\HttpFoundation\Response;

/**
 * ServiceProvider controller.
 *
 * @Route("/service-providers")
 */
class ServiceProviderController extends Controller
{
    use HasControllerUtils;
    use HasPagination;

    /**
     * Lists all ServiceProvider entities.
     *
     * @Route("/", name="platform_serviceProvider_list")
     * @Method("GET")
     * @Template("PlatformBundle:service-provider:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $serviceProviders = $em->getRepository('CondoBundle:ServiceProvider')
            ->findAll();

        $serviceProvidersPagination = $this->createPagination(
            $serviceProviders,
            $request
        );

        return [
            'serviceProviders' => $serviceProvidersPagination,
        ];
    }

    /**
     * Creates a new ServiceProvider entity.
     *
     * @Route("/new", name="platform_serviceProvider_new")
     * @Method({"GET", "POST"})
     * @Template("PlatformBundle:service-provider:new.html.twig")
     *
     * @param Request $request
     *
     * @return array|RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $serviceProvider = new ServiceProvider();
        $form = $this->createForm('PlatformBundle\Form\ServiceProviderType', $serviceProvider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($serviceProvider);
            $em->flush();

            return $this->redirectToRoute(
                'platform_serviceProvider_show',
                ['id' => $serviceProvider->getId()]
            );
        }

        return [
            'serviceProvider' => $serviceProvider,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a ServiceProvider entity.
     *
     * @Route("/{id}", name="platform_serviceProvider_show")
     * @Method("GET")
     * @Template("PlatformBundle:service-provider:show.html.twig")
     *
     * @param ServiceProvider $serviceProvider
     *
     * @return array|Response
     */
    public function showAction(ServiceProvider $serviceProvider)
    {
        $deleteForm = $this->createDeleteForm($serviceProvider);

        return [
            'serviceProvider' => $serviceProvider,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing ServiceProvider entity.
     *
     * @Route("/{id}/edit", name="platform_serviceProvider_edit")
     * @Method({"GET", "POST"})
     * @Template("PlatformBundle:service-provider:edit.html.twig")
     *
     * @param Request         $request
     * @param ServiceProvider $serviceProvider
     *
     * @return array|RedirectResponse|Response
     */
    public function editAction(Request $request, ServiceProvider $serviceProvider)
    {
        $deleteForm = $this->createDeleteForm($serviceProvider);
        $editForm = $this->createForm('PlatformBundle\Form\ServiceProviderType', $serviceProvider);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($serviceProvider);
            $em->flush();

            return $this->redirectToRoute(
                'platform_serviceProvider_edit',
                ['id' => $serviceProvider->getId()]
            );
        }

        return [
            'serviceProvider' => $serviceProvider,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a ServiceProvider entity.
     *
     * @Route("/{id}", name="platform_serviceProvider_delete")
     * @Method("DELETE")
     *
     * @param Request         $request
     * @param ServiceProvider $serviceProvider
     *
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, ServiceProvider $serviceProvider)
    {
        $form = $this->createDeleteForm($serviceProvider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($serviceProvider);
            $em->flush();
        }

        return $this->redirectToRoute('platform_serviceProvider_list');
    }

    /**
     * Creates a form to delete a ServiceProvider entity.
     *
     * @param ServiceProvider $serviceProvider The ServiceProvider entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ServiceProvider $serviceProvider)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl(
                    'platform_serviceProvider_delete',
                    ['id' => $serviceProvider->getId()]
                )
            )
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
