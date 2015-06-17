<?php

namespace SiteApi\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use SiteApi\Service\SiteSlotUsageService;
use Zend\Http\Request;

/**
 * Class SiteSlotUsageController
 *
 * @package SlotsApi\Controller\Report
 */
class SiteSlotUsageController extends AbstractDvsaRestfulController
{

    const SEARCH_DATE_FROM_QUERY_PARAM = 'dateFrom';
    const SEARCH_DATE_TO_QUERY_PARAM = 'dateTo';

    /**
     * Search for MotTests by site number, tester id or tester username.
     */
    public function getList()
    {
        /**
         * @var $service SiteSlotUsageService
         */
        $service = $this->getServiceLocator()->get(SiteSlotUsageService::class);

        $searchParams = $this->buildSearchParams($service);

        return ApiResponse::jsonOk(
            $service->getList(
                $searchParams,
                $service->getOutputFormat($searchParams)
            )
        );
    }

    public function periodDataAction()
    {

        /**
         * @var $service SiteSlotUsageService
         */
        $service = $this->getServiceLocator()->get(SiteSlotUsageService::class);

        $periodDates = $this->params()->fromQuery('period');
        $vtsId = $this->params()->fromRoute('siteId');

        $usage = [];

        foreach ($periodDates as $period) {
            $usage[] = $service->getSlotUsage($vtsId, $period['from'], $period['to']);
        }

        return ApiResponse::jsonOk($usage);
    }

    /**
     * Build the search params from the current request
     *
     * @param $service
     *
     * @return mixed
     */
    protected function buildSearchParams(SiteSlotUsageService $service)
    {
        /** @var Request $request */
        $request        = $this->getRequest();

        $dateFrom = (string)$request->getQuery(self::SEARCH_DATE_FROM_QUERY_PARAM);
        $dateTo = (string)$request->getQuery(self::SEARCH_DATE_TO_QUERY_PARAM);

        $request        = $this->getRequest();
        $searchParams = $service
            ->getSearchParams()
            ->setVtsId($this->params()->fromRoute('siteId'))
            ->setDateFrom($dateFrom)
            ->setDateTo($dateTo)
            ->loadStandardDataTableValuesFromRequest($request);

        return $searchParams;
    }
}
