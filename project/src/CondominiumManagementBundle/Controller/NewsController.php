<?php

namespace CondominiumManagementBundle\Controller;

use CondoBundle\Traits\HasPagination;
use CondoBundle\Entity\Condominium;
use CondoBundle\Entity\CondominiumNews;
use CondominiumManagementBundle\Traits\HasCondominiumManagementUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use CondoBundle\Entity\DatabaseFile;
use DateTime;

/**
 * @Route("{condominium}/news")
 */
class NewsController extends Controller
{
    use HasCondominiumManagementUtils;
    use HasPagination;

    /**
     * @Route("/", name="condominium_news_list")
     * @Method("GET")
     * @Template("CondominiumManagementBundle:News:list.html.twig")
     *
     * @param Request     $request
     * @param Condominium $condominium
     *
     * @return array
     */
    public function listAction(Request $request, Condominium $condominium)
    {
        $this->assertCanAccessCondominium($condominium);

        $newses = $this->getCondominiumNewsRepository()
            ->findBy(
                ['condominium' => $condominium],
                ['creationDate' => 'DESC']
            )
        ;

        $newsesPagination = $this->createPagination(
            $newses,
            $request
        );

        return $this->getResponseParameters(
            [
                'condominium' => $condominium,
                'newses' => $newsesPagination,
            ]
        );
    }

    /**
     * Creates a new ShopItem entity.
     *
     * @Route("/new", name="condominium_news_new")
     * @Method({"GET", "POST"})
     * @Template()
     *
     * @param Request     $request
     * @param Condominium $condominium
     *
     * @return array|RedirectResponse
     */
    public function newAction(Request $request, Condominium $condominium)
    {
        $this->assertCanAccessCondominium($condominium);

        $news = new CondominiumNews();
        $news->setCondominium($condominium);
        $news->setAuthor($this->getUser());
        $form = $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumNewsType',
            $news
        );
        $form->handleRequest($request);

        if (!$form->isSubmitted() && !$form->isValid()) {
            return $this->getResponseParameters(
                [
                    'condominium' => $condominium,
                    'form' => $form->createView(),
                ]
            );
        }

        $picture = $form->get('picture')->getData();
        $publishDate = $form->get('publishDate')->getData();
        $endDate = $form->get('endDate')->getData();
        $publishDate = new DateTime($publishDate);
        $news->setPublishDate($publishDate);

        if ($endDate !== null) {
            $endDate = new DateTime($endDate);
            $news->setEndDate($endDate);
        }

        if ($picture !== null) {
            $databasefile = new DatabaseFile($picture);
            $this->persistAndFlush($databasefile);
            $news->setPicture($databasefile);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($news);
        $em->flush();

        return $this->redirectToRoute(
            'condominium_news_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );
    }

    /**
     * Deletes a condominium news.
     *
     * @Route("/{news}", name="condominium_news_delete")
     * @Method("DELETE")
     *
     * @param CondominiumNews $news
     * @param Condominium     $condominium
     *
     * @return RedirectResponse
     */
    public function deleteAction(
        CondominiumNews $news,
        Condominium $condominium
    ) {
        $this->assertCanAccessCondominium($condominium, $news);

        $em = $this->getDoctrine()->getManager();
        $em->remove($news);
        $em->flush();

        return $this->redirectToRoute(
            'condominium_news_list',
            [
                'condominium' => $condominium->getId(),
            ]
        );
    }

    /**
     * @Route("/{news}/edit", name="condominium_news_edit")
     * @Method({"GET", "POST"})
     * @Template("CondominiumManagementBundle:News:edit.html.twig")
     *
     * @param Request         $request
     * @param Condominium     $condominium
     * @param CondominiumNews $CondominiumNews
     *
     * @return array|RedirectResponse
     */
    public function editAction(
        Request $request,
        Condominium $condominium,
        CondominiumNews $news
    ) {
        $this->assertCanAccessCondominium($condominium, $news);

        $form = $this->createForm(
            'CondominiumManagementBundle\Form\CondominiumNewsType',
            $news
        );
        $form->handleRequest($request);

        if (!$form->isSubmitted() && !$form->isValid()) {
            return $this->getResponseParameters([
                'condominium' => $condominium,
                'news' => $news,
                'form' => $form->createView(),
            ]);
        }

        $picture = $form->get('picture')->getData();
        $publishDate = $form->get('publishDate')->getData();
        $endDate = $form->get('endDate')->getData();
        $publishDate = new DateTime($publishDate);
        $news->setPublishDate($publishDate);

        if ($endDate !== null) {
            $endDate = new DateTime($endDate);
            $news->setEndDate($endDate);
        }

        if ($picture !== null) {
            $databasefile = new DatabaseFile($picture);
            $this->persistAndFlush($databasefile);
            $news->setPicture($databasefile);
        }
        $news->setCondominium($condominium);

        $this->persistAndFlush($news);

        return $this->redirectToRoute(
            'condominium_news_edit',
            [
                'condominium' => $condominium->getId(),
                'news' => $news->getId(),
            ]
        );
    }

    /**
     * @Route("/{news}", name="condominium_news_show")
     * @Method("GET")
     * @Template("CondominiumManagementBundle:News:show.html.twig")
     *
     * @param CondominiumNews $news
     * @param Condominium     $condominium
     *
     * @return array
     */
    public function showAction(
        CondominiumNews $news,
        Condominium $condominium)
    {
        $this->assertCanAccessCondominium($condominium, $news);

        return $this->getResponseParameters([
            'news' => $news,
            'condominium' => $condominium,
        ]);
    }
}
