<?php

namespace OrganisationApi\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use OrganisationApi\Service\OrganisationSlotUsageService;

/**
 * Class OrganisationSlotUsageController
 *
 * Produces the first layer of the slot usage report, the second layer is
 * handled by OrganisationSlotUsageDetailsController
 *
 * @package OrganisationApi\Controller
 */
class OrganisationSlotUsageController extends AbstractDvsaRestfulController
{

    const SEARCH_DATE_FROM_QUERY_PARAM = 'dateFrom';
    const SEARCH_DATE_TO_QUERY_PARAM   = 'dateTo';
    const SEARCH_TEXT_QUERY_PARAM      = 'searchText';

    public function getList()
    {
        /**
         * @var $service OrganisationSlotUsageService
         */
        $service = $this->getServiceLocator()->get(OrganisationSlotUsageService::class);

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
         * @var $service OrganisationSlotUsageService
         */
        $service = $this->getServiceLocator()->get(OrganisationSlotUsageService::class);

        $periodDates = $this->params()->fromQuery('period');

        $orgId = $this->params()->fromRoute('organisationId');

        foreach ($periodDates as $period) {
            $usage[] = $service->getSlotUsage($orgId, $period['from'], $period['to']);
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
    protected function buildSearchParams(OrganisationSlotUsageService $service)
    {
        $request = $this->getRequest();

        $dateFrom   = (string) $request->getQuery(self::SEARCH_DATE_FROM_QUERY_PARAM);
        $dateTo     = (string) $request->getQuery(self::SEARCH_DATE_TO_QUERY_PARAM);
        $searchText = (string) $request->getQuery(self::SEARCH_TEXT_QUERY_PARAM);

        $searchParams = $service
            ->getSearchParams()
            ->setOrganisationId($this->params()->fromRoute('organisationId'))
            ->setDateFrom($dateFrom)
            ->setDateTo($dateTo)
            ->setSearchText($searchText)
            ->loadStandardDataTableValuesFromRequest($request);

        return $searchParams;
    }
}
