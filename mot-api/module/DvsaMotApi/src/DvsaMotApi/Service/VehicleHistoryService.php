<?php

namespace DvsaMotApi\Service;

use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Date\DateTimeHolder;
use DvsaCommon\Date\DateUtils;
use DvsaCommon\Dto\Vehicle\History\VehicleHistoryDto;
use DvsaCommon\Dto\Vehicle\History\VehicleHistoryItemDto;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaEntities\Repository\ConfigurationRepository;
use DvsaEntities\Repository\MotTestRepository;
use DvsaMotApi\Mapper\VehicleHistoryApiMapper;

/**
 * Class VehicleHistoryService.
 */
class VehicleHistoryService
{
    const MODIFICATION_WINDOW_LENGTH_IN_DAYS = 7;

    protected $authService;
    protected $configurationRepository;
    protected $dateTimeHolder;
    protected $motTestRepository;

    /**
     * @param \DvsaEntities\Repository\MotTestRepository        $motTestRepository
     * @param \DvsaCommon\Auth\MotAuthorisationServiceInterface $authService
     * @param \DvsaEntities\Repository\ConfigurationRepository  $configurationRepository
     */
    public function __construct(
        MotTestRepository $motTestRepository,
        MotAuthorisationServiceInterface $authService,
        ConfigurationRepository $configurationRepository
    ) {
        $this->motTestRepository       = $motTestRepository;
        $this->authService             = $authService;
        $this->configurationRepository = $configurationRepository;

        $this->dateTimeHolder = new DateTimeHolder();
    }

    /**
     * Returns a list of tests for a given vehicle as of a specified date.
     *
     * @param int       $vehicleId
     * @param \DateTime $startDate
     *
     * @return \DvsaCommon\Dto\Vehicle\History\VehicleHistoryDto
     */
    public function findHistoricalTestsForVehicleSince($vehicleId, \DateTime $startDate = null)
    {
        $this->authService->assertGranted(PermissionInSystem::VEHICLE_MOT_TEST_HISTORY_READ);

        if (
            !$this->authService->isGranted(PermissionInSystem::FULL_VEHICLE_MOT_TEST_HISTORY_VIEW)
            && $startDate === null
        ) {
            $maxHistoryLength = (int) $this->configurationRepository->getValue(
                MotTestService::CONFIG_PARAM_MAX_VISIBLE_VEHICLE_TEST_HISTORY_IN_MONTHS
            );
            $startDate = DateUtils::subtractCalendarMonths($this->dateTimeHolder->getCurrentDate(), $maxHistoryLength);
        }
        $testsHistory = $this->motTestRepository->findHistoricalTestsForVehicle($vehicleId, $startDate);

        $vehicleHistoryDto = (new VehicleHistoryApiMapper())->fromArrayOfObjectsToDto($testsHistory);

        $this->setAllowEditForSelectedItems($vehicleHistoryDto);

        return $vehicleHistoryDto;
    }

    /**
     * Iterates through list of VehicleHistoryItemDto and marks items as eligible to issue replacement certificate
     *  when the item meets criteria:
     * - status equals PASSED or FAILED
     * - user has CERTIFICATE_REPLACEMENT_FULL permission
     * - there is no more recent test
     * or
     * - status equals PASSED or FAILED
     * - test type equals NORMAL or RETEST
     * - number of days that passed since issue date is lower then 7
     * - no previous item has been marked as eligible to issue replacement certificate.
     *
     * @param VehicleHistoryDto $vehicleHistoryDto
     */
    protected function setAllowEditForSelectedItems(VehicleHistoryDto $vehicleHistoryDto)
    {
        if (!$this->authService->isGranted(PermissionInSystem::CERTIFICATE_REPLACEMENT)) {
            return;
        };

        $hasFullReplacementPermission = $this->authService->isGranted(PermissionInSystem::CERTIFICATE_REPLACEMENT_FULL);
        $hasAlreadyMarkedSomePreviousItemAsEligible = false;

        /** @var VehicleHistoryItemDto $item */
        foreach ($vehicleHistoryDto->getIterator() as $item) {
            $isStatusEditable = in_array(
                $item->getStatus(),
                [MotTestStatusName::PASSED, MotTestStatusName::FAILED]
            );

            $isTestTypeEditable = in_array(
                $item->getTestType(),
                [MotTestTypeCode::NORMAL_TEST, MotTestTypeCode::RE_TEST]
            );

            $numberOfDaysPassedSinceTestIssue = $item->getIssuedDate()
                ? (int)DateUtils::getDaysDifference(
                    DateUtils::cropTime(DateUtils::toDateTime($item->getIssuedDate())),
                    $this->dateTimeHolder->getCurrentDate()
                )
                : NULL;

            $isMostRecentNonAbandoned = $vehicleHistoryDto->isMostRecentNonAbandoned($item);

            $isEligibleToIssueReplacement = $this->isPossibleToIssueReplacement(
                $isStatusEditable,
                $hasFullReplacementPermission,
                $isTestTypeEditable,
                $numberOfDaysPassedSinceTestIssue,
                $hasAlreadyMarkedSomePreviousItemAsEligible,
                $isMostRecentNonAbandoned
            );

            $item->setAllowEdit($isEligibleToIssueReplacement);

            $hasAlreadyMarkedSomePreviousItemAsEligible = $hasAlreadyMarkedSomePreviousItemAsEligible
                || $isEligibleToIssueReplacement;
        }
    }

    /**
     * @param bool $isStatusEditable
     * @param bool $hasFullReplacementPermission
     * @param bool $isTestTypeEditable
     * @param int  $numberOfDaysPassedSinceTestIssue
     * @param bool $hasAlreadyMarkedSomePreviousItemAsEligible
     * @param bool $hasMoreRecentTest
     *
     * @return bool
     */
    protected function isPossibleToIssueReplacement(
        $isStatusEditable,
        $hasFullReplacementPermission,
        $isTestTypeEditable,
        $numberOfDaysPassedSinceTestIssue,
        $hasAlreadyMarkedSomePreviousItemAsEligible,
        $isMostRecentNonAbandoned
    ) {
        $isAllowEdit = false;

        if ($isStatusEditable && $isMostRecentNonAbandoned) {
            if ($hasFullReplacementPermission) {
                $isAllowEdit = true;
            } elseif (
                $isTestTypeEditable
                && $numberOfDaysPassedSinceTestIssue <= self::MODIFICATION_WINDOW_LENGTH_IN_DAYS
                && $hasAlreadyMarkedSomePreviousItemAsEligible === false
            ) {
                $isAllowEdit = true;
            }
        }
        return $isAllowEdit;
    }
}
