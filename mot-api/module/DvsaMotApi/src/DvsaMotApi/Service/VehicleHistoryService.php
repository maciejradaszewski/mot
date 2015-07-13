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
     *  when the item meets criteria:.
     *
     * - there is no more recent test
     * - no previous item has been marked as eligible to issue replacement certificate.
     *
     * @param VehicleHistoryDto $vehicleHistoryDto
     */
    protected function setAllowEditForSelectedItems(VehicleHistoryDto $vehicleHistoryDto)
    {
        if (!$this->authService->isGranted(PermissionInSystem::CERTIFICATE_REPLACEMENT)) {
            return;
        };

        $grantedFullCertReplacement = $this->authService->isGranted(PermissionInSystem::CERTIFICATE_REPLACEMENT_FULL);

        $history = $vehicleHistoryDto->getIterator();

        if (!$history) {
            return;
        }

        $history->uasort(function (VehicleHistoryItemDto $a, VehicleHistoryItemDto $b) {

            if ($a->getPrsMotTestId() &&
                $a->getPrsMotTestId() == $b->getId() &&
                $b->getPrsMotTestId() == $a->getId()
            ) {
                // For PRS: Passed first
                return $a->getStatus() == MotTestStatusName::FAILED ? 1 : -1;
            }

            if ($a->getIssuedDate() == $b->getIssuedDate()) {
                // By id if dates the same
                return $a->getId() < $b->getId() ? 1 : -1;
            }

            // Descending
            return $a->getIssuedDate() < $b->getIssuedDate() ? 1 : -1;
        });

        $alreadyAssigned = FALSE;

        /** @var VehicleHistoryItemDto $item */
        foreach ($history as $index=>$item) {
            $allowedEdit = $this->isEditAllowedForHistoryItem($item);

            if ($grantedFullCertReplacement) {
                $item->setAllowEdit($allowedEdit);
            } else {
                $item->setAllowEdit($alreadyAssigned ? FALSE : $allowedEdit);
                $alreadyAssigned = $alreadyAssigned ? TRUE : $allowedEdit;
            }

            if (in_array($item->getTestType(), [
                MotTestTypeCode::TARGETED_REINSPECTION,
                MotTestTypeCode::STATUTORY_APPEAL,
                MotTestTypeCode::INVERTED_APPEAL,
            ])) {
                $alreadyAssigned = TRUE;
            }
        }

        foreach ($history as $index=>$item) {
            // Remove targeted reinspection from the list
            if ($item->getTestType() == MotTestTypeCode::TARGETED_REINSPECTION) {
                $history->offsetUnset($index);
            }
        }
    }

    /**
     * Edit is allowed when:.
     *
     * - status equals PASSED or FAILED
     * - user has CERTIFICATE_REPLACEMENT_FULL permission
     * - user has CERTIFICATE_REPLACEMENT permission AND...
     * - status equals PASSED or FAILED
     * - test type equals NORMAL or RETEST
     * - certificate did not expired (there is a requirement that colour of the vehicle
     *   should be editable for the lifetime of certificate)
     *
     * @param VehicleHistoryItemDto $item Item to be checked
     *
     * @throws \DvsaCommon\Date\Exception\IncorrectDateFormatException
     *
     * @return bool
     */
    protected function isEditAllowedForHistoryItem(VehicleHistoryItemDto $item)
    {
        $grantedFullCertReplacement = $this->authService->isGranted(PermissionInSystem::CERTIFICATE_REPLACEMENT_FULL);

        if (!$this->authService->isGranted(PermissionInSystem::CERTIFICATE_REPLACEMENT)) {
            return FALSE;
        }

        if (!in_array($item->getStatus(), [MotTestStatusName::PASSED, MotTestStatusName::FAILED])) {
            return FALSE;
        }

        if ($grantedFullCertReplacement) {
            return true;
        }

        if (!in_array($item->getTestType(),
            [
                MotTestTypeCode::NORMAL_TEST,
                MotTestTypeCode::RE_TEST,
            ])
        ) {
            return FALSE;
        };

        $currentDate = DateUtils::today();
        $expiryDate  = $item->getExpiryDate() ?: NULL;

        if ($expiryDate && DateUtils::compareDates($expiryDate, $currentDate) <= 0) {
            return FALSE;
        }

        return TRUE;
    }
}
