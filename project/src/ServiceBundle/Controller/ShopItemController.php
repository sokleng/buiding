<?php

namespace ServiceBundle\Controller;

use CondoBundle\Entity\DatabaseFile;
use CondoBundle\Entity\Service;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ServiceBundle\Traits\HasServiceControllerUtils;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use GenericOrderingServiceBundle\Entity\ShopItem;
use Symfony\Component\HttpFoundation\Response;

/**
 * ShopItem controller.
 *
 * @Route("/{service}/products")
 */
class ShopItemController extends Controller
{
    use HasServiceControllerUtils;

    /**
     * Lists all Shop Items for a given service.
     *
     * @Route("/", name="service_products_list")
     * @Method("GET")
     * @Template("ServiceBundle:ShopItem:index.html.twig")
     *
     * @param Service $service
     *
     * @return array|Response
     */
    public function indexAction(Service $service)
    {
        $this->assertUserCanAccessService($service);

        $em = $this->getDoctrine()->getManager();

        $shopItems = $em->getRepository('GenericOrderingServiceBundle:ShopItem')
            ->findBy(
                ['service' => $service],
                ['name' => 'ASC']
            );

        return [
            'services' => $this->getManagerServices(),
            'service' => $service,
            'cartItems' => $shopItems,
        ];
    }

    /**
     * Creates a new ShopItem entity.
     *
     * @Route("/new", name="service_products_new")
     * @Method({"GET", "POST"})
     * @Template("ServiceBundle:ShopItem:new.html.twig")
     *
     * @param Request $request
     * @param Service $service
     *
     * @return array|RedirectResponse
     */
    public function newAction(Request $request, Service $service)
    {
        $this->assertUserCanAccessService($service);

        $shopItem = new ShopItem();
        $shopItem->setService($service);
        $form = $this->createForm('ServiceBundle\Form\ShopItemType', $shopItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $picture */
            $picture = $form->get('picture')->getData();
            $shopItem->setPicture(new DatabaseFile($picture));

            $em = $this->getDoctrine()->getManager();
            $em->persist($shopItem);
            $em->flush();

            return $this->redirectToRoute(
                'service_products_show',
                [
                    'id' => $shopItem->getId(),
                    'service' => $service->getId(),
                ]
            );
        }

        return [
            'services' => $this->getManagerServices(),
            'service' => $service,
            'shopItem' => $shopItem,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a ShopItem entity.
     *
     * @Route("/{id}", name="service_products_show")
     * @Method("GET")
     * @Template("ServiceBundle:ShopItem:show.html.twig")
     *
     * @param ShopItem $shopItem
     * @param Service  $service
     *
     * @return array
     */
    public function showAction(ShopItem $shopItem, Service $service)
    {
        $this->assertUserCanAccessService($service, $shopItem);

        return [
            'services' => $this->getManagerServices(),
            'service' => $service,
            'shopItem' => $shopItem,
        ];
    }

    /**
     * Displays a form to edit an existing ShopItem entity.
     *
     * @Route("/{id}/edit", name="service_products_edit")
     * @Method({"GET", "POST"})
     * @Template("ServiceBundle:ShopItem:edit.html.twig")
     *
     * @param Request  $request
     * @param ShopItem $shopItem
     * @param Service  $service
     *
     * @return array|RedirectResponse
     */
    public function editAction(
        Request $request,
        ShopItem $shopItem,
        Service $service
    ) {
        $this->assertUserCanAccessService($service, $shopItem);

        $editForm = $this->createForm('ServiceBundle\Form\ShopItemType', $shopItem);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            /** @var UploadedFile $picture */
            $picture = $editForm->get('picture')->getData();
            $shopItem->setPicture(new DatabaseFile($picture));

            $em = $this->getDoctrine()->getManager();
            $em->persist($shopItem);
            $em->flush();

            return $this->redirectToRoute(
                'service_products_edit',
                [
                    'id' => $shopItem->getId(),
                    'service' => $service->getId(),
                ]
            );
        }

        return [
            'services' => $this->getManagerServices(),
            'service' => $service,
            'shopItem' => $shopItem,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing ShopItem entity.
     *
     * @Route("/{shopItem}/changestatus", name="change_status_shop_item")
     * @Method({"GET", "POST"})
     * @Template("ServiceBundle:ShopItem:changeItemStatus.html.twig")
     *
     * @param Request  $request
     * @param ShopItem $shopItem
     * @param Service  $service
     *
     * @return array|RedirectResponse
     */
    public function changeItemStatusAction(
        Request $request,
        ShopItem $shopItem,
        Service $service
    ) {
        $this->assertUserCanAccessService($service, $shopItem);

        $em = $this->getDoctrine()->getManager();
        $status = $shopItem->isEnabled();
        $shopItem->setEnabled(!$status);
        $em->persist($shopItem);
        $em->flush();

        return $this->redirectToRoute(
            'service_products_list',
            [
                'service' => $service->getId(),
            ]
        );
    }

    /**
     * Deletes a ShopItem entity.
     *
     * @Route("/{id}", name="service_products_delete")
     * @Method("DELETE")
     *
     * @param ShopItem $shopItem
     * @param Service  $service
     *
     * @return RedirectResponse
     */
    public function deleteAction(
        ShopItem $shopItem,
        Service $service
    ) {
        $this->assertUserCanAccessService($service, $shopItem);

        $em = $this->getDoctrine()->getManager();
        $em->remove($shopItem);
        $em->flush();

        return $this->redirectToRoute(
            'service_products_list',
            [
                'service' => $service->getId(),
            ]
        );
    }
}
