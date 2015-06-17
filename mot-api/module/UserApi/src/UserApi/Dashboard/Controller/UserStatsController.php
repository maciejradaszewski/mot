<?php

namespace UserApi\Dashboard\Controller;

use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;
use UserApi\Dashboard\Service\UserStatsService;

/**
 * Returns testing statistics
 */
class UserStatsController extends AbstractDvsaRestfulController
{
    public function get($id)
    {
        $service = $this->getUserStatsService();

        $dayStats = $service->getUserDayStatsByPersonId($id)->toArray();
        $monthStats = $service->getUserCurrentMonthStatsByPersonId($id)->toArray();

        $result = array_merge($dayStats, $monthStats);

        return ApiResponse::jsonOk($result);
    }

    /**
     * @return UserStatsService
     */
    private function getUserStatsService()
    {
        return $this->getServiceLocator()->get(UserStatsService::class);
    }
}
