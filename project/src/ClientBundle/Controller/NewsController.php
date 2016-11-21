<?php

namespace ClientBundle\Controller;

use CondoBundle\Entity\Unit;
use CondoBundle\Entity\News;
use ClientBundle\Traits\HasClientControllerUtils;
use CondoBundle\Traits\HasPagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/units/{unit}/news")
 */
class NewsController extends Controller
{
    use HasClientControllerUtils;
    use HasPagination;

    /**
     * @Route("/", name="client_news_list")
     * @Method("GET")
     * @Template("ClientBundle:News:list.html.twig")
     *
     * @param Request $request
     * @param Unit    $unit
     *
     * @return array
     */
    public function listAction(
        Request $request,
        Unit $unit
    ) {
        $this->assertCanAccessUnit($unit);
        $newses = $this->getCondominiumNewsRepository()
            ->findAllForUnit($unit)
        ;

        $condosPagination = $this->createPagination(
            $newses,
            $request
        );

        return $this->getResponseParameters(
            [
                'newses' => $condosPagination,
                'unit' => $unit,
            ]
        );
    }

    /**
     * @Route("/readmore/{news}", name="client_news_read_more")
     * @Method("GET")
     * @Template("ClientBundle:News:detail.html.twig")
     *
     * @param Request $request
     * @param New     $new
     * @param Unit    $unit
     *
     * @return getResponseParameters
     */
    public function detailAction(
        Request $request,
        News $news,
        Unit $unit
    ) {
        return $this->getResponseParameters(
            [
                'news' => $news,
                'unit' => $unit,
            ]
        );
    }
}
