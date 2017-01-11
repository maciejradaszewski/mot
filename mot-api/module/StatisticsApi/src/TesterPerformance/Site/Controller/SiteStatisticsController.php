<?php

namespace Dvsa\Mot\Api\StatisticsApi\TesterPerformance\Site\Controller;

use Dvsa\Mot\Api\StatisticsApi\TesterPerformance\Site\Service\TesterStatisticsService;
use DvsaCommon\Constants\FeatureToggle;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;

class SiteStatisticsController extends AbstractDvsaRestfulController implements AutoWireableInterface
{
    private $siteStatisticsService;

    function __construct(TesterStatisticsService $siteStatisticsService)
    {
        $this->siteStatisticsService = $siteStatisticsService;
    }

    public function get($siteId)
    {
        if (!$this->isFeatureEnabled(FeatureToggle::TEST_QUALITY_INFORMATION)) {
            return ApiResponse::jsonOk();
        }

        $year = (int)$this->params()->fromRoute("year");
        $month = (int)$this->params()->fromRoute("month");

        $dto = $this->siteStatisticsService->getForSite($siteId, $year, $month);

        return $this->returnDto($dto);
    }
}
