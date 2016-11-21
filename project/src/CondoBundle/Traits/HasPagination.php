<?php

namespace CondoBundle\Traits;

use Doctrine\ORM\Query;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\QueryBuilder;

/**
 * @method get(string $object)
 */
trait HasPagination
{
    private static $DEFAULT_ITEM_PER_PAGE = 15;
    private static $DEFAULT_PAGINATION_THEME = 'pagination.html.twig';
    private static $DIR_PAGINATION = 'CondoBundle:Pagination:';

    /**
     * Create a pagination object to paginate a list of entities.
     *
     * @param Query|QueryBuilder|array $entities
     * @param Request                  $request
     * @param int                      $itemPerPage
     * @param string                   $customPaginationTheme
     *
     * @return SlidingPagination
     */
    private function createPagination(
        $entities,
        Request $request,
        $itemPerPage = null,
        $customPaginationTheme = null
    ) {
        if (!empty($itemPerPage)) {
            $ITEM_PER_PAGE = $itemPerPage;
        } else {
            $ITEM_PER_PAGE = self::$DEFAULT_ITEM_PER_PAGE;
        }

        /** @var Paginator $paginator */
        $paginator = $this->get('knp_paginator');

        /** @var SlidingPagination $pagination */
        $pagination = $paginator->paginate(
            $entities,
            $request->query->get('page', 1),
            $ITEM_PER_PAGE
        );

        if (!empty($customPaginationTheme)) {
            $paginationTheme = $customPaginationTheme;
        } else {
            $paginationTheme = self::$DEFAULT_PAGINATION_THEME;
        }

        $pagination->setTemplate(self::$DIR_PAGINATION.$paginationTheme);

        return $pagination;
    }
}
