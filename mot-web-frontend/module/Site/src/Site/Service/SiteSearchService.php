<?php

namespace Site\Service;

use DvsaCommon\Dto\Site\SiteListDto;
use DvsaCommon\UrlBuilder\SiteUrlBuilderWeb;
use Report\Table\Formatter\SubRow;
use Report\Table\Table;
use Report\Table\Formatter\SiteLink;
use Report\Table\Formatter\Subline;
use Zend\Http\Request;

/**
 * Class SiteSearchService
 * @package Site\Service
 */
class SiteSearchService
{
    public function initTable(SiteListDto $result)
    {
        return (new Table())->setColumns(
            [
                // Site Number + link Column
                [
                    'title'   => 'Site',
                    'sortBy'  => 'siteNumber',
                    'field'  => 'site_number',
                    'formatter' => SiteLink::class,
                ],
                // Site Name and Phone Column
                [
                    'title'    => 'Name/phone',
                    'sortBy' => 'siteName',
                    'sub'    => [
                        [
                            'field'     => 'name',
                        ],
                        [
                            'field'     => 'phone',
                            'formatter' => SubRow::class,
                        ],
                    ],
                ],
                // Address Town and Postcode Column
                [
                    'title'    => 'City/postcode',
                    'sortBy' => 'siteTownPostcode',
                    'sub'    => [
                        [
                            'field'     => 'town',
                        ],
                        [
                            'field'     => 'postcode',
                            'formatter' => SubRow::class,
                        ],
                    ],
                ],
                // Site Classes Column
                [
                    'title'   => 'Classes',
                    'sortBy'  => 'siteClasses',
                    'field'  => 'roles',
                ],
                // Site Type and Status Column
                [
                    'title'    => 'Type/status',
                    'sortBy' => 'siteTypeStatus',
                    'sub'    => [
                        [
                            'field'     => 'type',
                        ],
                        [
                            'field'     => 'status',
                            'formatter' => SubRow::class,
                        ],
                    ],
                ],
            ]
        )
            ->setData($result->getData())
            ->setRowsTotalCount($result->getTotalResultCount())
            ->setSearchParams($result->getSearched());
    }

    /**
     * Build the url with the request
     *
     * @param SiteUrlBuilderWeb $url
     * @param Request $request
     * @return string
     */
    public function buildParams(SiteUrlBuilderWeb $url, Request $request)
    {
        return $url . '?' . http_build_query($request->getQuery()->toArray());
    }
}