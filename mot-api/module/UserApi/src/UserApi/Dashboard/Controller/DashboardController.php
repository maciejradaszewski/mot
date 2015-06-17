<?php

namespace UserApi\Dashboard\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use UserApi\Dashboard\Dto\DashboardData;
use UserApi\Dashboard\Service\DashboardService;

/**
 * Returns all data for dashboard
 */
class DashboardController extends AbstractDvsaRestfulController
{
    public function get($id)
    {
        /** @var $service DashboardService */
        $service = $this->getServiceLocator()->get(DashboardService::class);

        /** @var $result DashboardData */
        $result = $service->getDataForDashboardByPersonId($id);

        return ApiResponse::jsonOk($result->toArray());
    }
}
