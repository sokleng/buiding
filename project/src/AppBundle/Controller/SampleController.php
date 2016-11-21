<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Tgallice\Wit\Model\Context;

class SampleController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template("AppBundle:sample:homepage.html.twig")
     *
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $converseQuery = $request->get('converse_query');

        $result = [
            'converse_query' => $converseQuery,
        ];

        if (!empty($converseQuery)) {
            $ai = $this->get('app.wit_ai');

            /** @var Context $response */
            $response = $ai->converse(
                uniqid($this->getUser()->getId().'_'),
                $converseQuery
            );

            $result['converse_response'] = $response->get('response_message');
        }

        return $result;
    }

    /**
     * @Route("/sample/tables")
     * @Template
     */
    public function tablesAction()
    {
        return [
            'browserData' => $this->getFakeBrowserTableData(),
        ];
    }

    /**
     * @Route("/sample/forms")
     * @Template
     */
    public function formsAction()
    {
        return [];
    }

    /**
     * @Route("/sample/panels")
     * @Template
     */
    public function panelsAction()
    {
        return [];
    }

    /**
     * @Route("/sample/buttons")
     * @Template
     */
    public function buttonsAction()
    {
        return [];
    }

    /**
     * @Route("/sample/notifications")
     * @Template
     */
    public function notificationsAction()
    {
        return [];
    }

    /**
     * @Route("/sample/typography")
     * @Template
     */
    public function typographyAction()
    {
        return [];
    }

    /**
     * @Route("/sample/icons")
     * @Template
     */
    public function iconsAction()
    {
        return [];
    }

    /**
     * @Route("/sample/grid")
     * @Template
     */
    public function gridAction()
    {
        return [];
    }

    private function getFakeBrowserTableData()
    {
        $data = [];

        for ($i = 1; $i <= 100; ++$i) {
            $data[] = [
                'engine' => 'engine_'.$i,
                'browser' => 'browser_'.$i,
                'platform' => 'platform_'.$i,
                'engineVersion' => 'v'.$i,
                'grade' => $i,
            ];
        }

        return $data;
    }
}
