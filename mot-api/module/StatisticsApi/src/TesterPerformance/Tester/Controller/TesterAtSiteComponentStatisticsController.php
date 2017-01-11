<?php
namespace Dvsa\Mot\Api\StatisticsApi\TesterPerformance\Tester\Controller;

use Dvsa\Mot\Api\StatisticsApi\TesterPerformance\Tester\Service\TesterAtSiteComponentStatisticsService;
use DvsaCommon\Constants\FeatureToggle;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaCommonApi\Model\ApiResponse;

class TesterAtSiteComponentStatisticsController extends AbstractDvsaRestfulController implements AutoWireableInterface
{

    private $service;

    function __construct(TesterAtSiteComponentStatisticsService $componentStatisticsService)
    {
        $this->service = $componentStatisticsService;
        $this->setIdentifierName("testerId");
    }

    public function get($testerId)
    {
        if (!$this->isFeatureEnabled(FeatureToggle::TEST_QUALITY_INFORMATION)) {
            return ApiResponse::jsonOk();
        }

        $siteId = $this->params()->fromRoute('siteId');
        $group = $this->params()->fromRoute('group');
        $year = (int)$this->params()->fromRoute("year");
        $month = (int)$this->params()->fromRoute("month");

        $componentStatisticsDto = $this->service->get($siteId, $testerId, $group, $year, $month);

        return $this->returnDto($componentStatisticsDto);
    }
}