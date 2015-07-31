<?php

namespace Site\ViewModel;

use Core\ViewModel\Equipment\EquipmentViewModel;
use DvsaClient\Entity\SiteDailyOpeningHours;
use DvsaCommon\Date\DateTimeDisplayFormat;
use DvsaCommon\Date\Time;
use DvsaCommon\Dto\Equipment\EquipmentDto;
use DvsaCommon\Dto\MotTesting\MotTestInProgressDto;
use DvsaCommon\Dto\Site\SiteTestingDailyScheduleDto;
use DvsaCommon\Dto\Site\VehicleTestingStationDto;
use DvsaCommon\Utility\ArrayUtils;
use Site\Authorization\VtsOverviewPagePermissions;
use Site\ViewModel\MotTest\MotTestInProgressViewModel;
use Zend\View\Model\ViewModel;

/**
 * Class VTSDecorator
 *
 * @package Site\ViewModel
 */
class SiteViewModel
{
    const LIMIT_GREEN_THRESHOLD = 324.1;
    const LIMIT_AMBER_THRESHOLD = 459.2;

    /**
     * @var  VehicleTestingStationDto
     */
    private $site;
    /**
     * @var  EquipmentViewModel[]
     */
    private $equipments;
    /**
     * @var MotTestInProgressViewModel[]
     */
    private $testsInProgress;

    /**
     * @var VtsOverviewPagePermissions
     */
    private $permissions;

    /**
     * @param VehicleTestingStationDto      $site
     * @param EquipmentDto[]                $equipments
     * @param MotTestInProgressDto[]        $testsInProgress
     * @param VtsOverviewPagePermissions    $permissions
     * @param array                         $equipmentModelStatusMap
     */
    public function __construct(
        VehicleTestingStationDto $site,
        $equipments,
        $testsInProgress,
        VtsOverviewPagePermissions $permissions,
        $equipmentModelStatusMap
    ) {
        $this->site = $site;
        $this->permissions = $permissions;

        $this->setupEquipment($equipments, $equipmentModelStatusMap);
        $this->wrapTestsInProgress($testsInProgress);
    }

    /**
     * @param EquipmentDto[] $equipmentDto
     * @param $equipmentModelStatusMap
     */
    private function setupEquipment($equipmentDto, $equipmentModelStatusMap)
    {
        $this->equipments = ArrayUtils::map(
            $equipmentDto,
            function (EquipmentDto $equipmentDto) use ($equipmentModelStatusMap) {
                $modelStatus = $equipmentModelStatusMap[$equipmentDto->getModel()->getStatus()];
                return new EquipmentViewModel($equipmentDto, $modelStatus);
            }
        );
    }

    /**
     * @param MotTestInProgressDto[] $testsInProgress
     */
    private function wrapTestsInProgress($testsInProgress)
    {
        $this->testsInProgress = [];
        foreach ($testsInProgress as $testInProgress) {
            $this->testsInProgress[] = new MotTestInProgressViewModel($testInProgress);
        }
    }

    /**
     * @return VehicleTestingStationDto
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @return \Core\ViewModel\Equipment\EquipmentViewModel[]
     */
    public function getEquipments()
    {
        return $this->equipments;
    }

    /**
     * @return MotTest\MotTestInProgressViewModel[]
     */
    public function getTestsInProgress()
    {
        return $this->testsInProgress;
    }

    /**
     * @return VtsOverviewPagePermissions
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    public function getCountryToggle()
    {
        if ($this->site->isDualLanguage() == true) {
            return 'Wales';
        }
        if ($this->site->isScottishBankHoliday() == true) {
            return 'Scotland';
        }
        return 'England';
    }

    public function displayWeekday(SiteTestingDailyScheduleDto $schedule)
    {
        return SiteDailyOpeningHours::$DAY_NAMES[$schedule->getWeekday()];
    }

    public function displayOpeningHours(SiteTestingDailyScheduleDto $schedule)
    {
        if ($schedule->getOpenTime() === null && $schedule->getCloseTime() === null) {
            return 'Closed';
        }

        return DateTimeDisplayFormat::time(Time::fromIso8601($schedule->getOpenTime())) .
        ' to ' . DateTimeDisplayFormat::time(Time::fromIso8601($schedule->getCloseTime()));
    }
}
