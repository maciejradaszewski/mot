<?php

namespace DvsaMotApi\Service\Validator\RetestEligibility;

use DvsaCommon\Date\DateTimeHolder;
use DvsaCommon\Date\DateUtils as DU;
use DvsaCommon\Dto\MotTesting\ContingencyMotTestDto;
use DvsaCommonApi\Service\Exception\BadRequestException;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Repository\MotTestRepository;
use NonWorkingDaysApi\NonWorkingDaysHelper;

/**
 * Contains services related to retest eligibility feature
 */
class RetestEligibilityValidator
{
    const RETEST_WORKING_DAYS_PERIOD = 10;
    /** @var NonWorkingDaysHelper */
    private $nonWorkingDaysHelper;
    /** @var MotTestRepository $motTestRepository */
    private $motTestRepository;
    /** @var  DateTimeHolder */
    private $dateTime;

    /**
     * @param NonWorkingDaysHelper $nonWorkingDaysHelper
     * @param MotTestRepository $motTestRepository
     */
    public function __construct(
        NonWorkingDaysHelper $nonWorkingDaysHelper,
        MotTestRepository $motTestRepository
    ) {
        $this->nonWorkingDaysHelper = $nonWorkingDaysHelper;
        $this->motTestRepository = $motTestRepository;

        $this->dateTime = new DateTimeHolder();
    }

    /**
     * Method checks if a vehicle is eligible for a retest
     *
     * @param int $vehicleId database identifier of vehicle
     * @param int $vtsId database identifier of Vehicle Testing Station
     * @param ContingencyMotTestDto $contingencyDto
     *
     * @return int  Retest granted code, or throw exception
     * @throws BadRequestException
     */
    public function checkEligibilityForRetest($vehicleId, $vtsId, $contingencyDto = null)
    {
        $checkResult = $this->validateVehicleForRetest($vehicleId, $vtsId, $contingencyDto);

        $isEligibleForRetest = (count($checkResult) === 0);
        if (!$isEligibleForRetest) {
            $exception = BadRequestException::create("Vehicle is not eligible for a retest");

            foreach ($checkResult as $errorCode) {
                $errorMsg = RetestEligibilityErrorCodeTranslator::toText($errorCode);
                $exception->addError($errorMsg, $errorCode, $errorMsg);
            }

            throw $exception;
        }

        return $isEligibleForRetest;
    }

    /**
     * @param $vehicleId
     * @param $vtsId
     * @param $contingencyDto
     *
     * @return array
     */
    private function validateVehicleForRetest($vehicleId, $vtsId, $contingencyDto = null)
    {
        $resultCodes = [];
        $lastTest = $this->motTestRepository->findLastNormalTest($vehicleId, $contingencyDto);

        if (!($lastTest instanceof MotTest)) {
            return [RetestEligibilityCheckCode::RETEST_REJECTED_ORIGINAL_NEVER_PERFORMED];
        }

        if ($lastTest->isCancelled()) {
            return [RetestEligibilityCheckCode::RETEST_REJECTED_ORIGINAL_CANCELLED];
        } elseif (!$lastTest->isFailed()) {
            return [RetestEligibilityCheckCode::RETEST_REJECTED_ORIGINAL_WAS_NOT_FAILED];
        }

        if (false === $this->isWithinTenWorkingDays($lastTest, $contingencyDto)) {
            $resultCodes[] = RetestEligibilityCheckCode::RETEST_REJECTED_ORIGINAL_PERFORMED_MORE_THAN_10_WORKING_DAYS;
        }

        if (intval($lastTest->getVehicleTestingStation()->getId()) !== intval($vtsId)) {
            $resultCodes[] = RetestEligibilityCheckCode::RETEST_REJECTED_ORIGINAL_PERFORMED_AT_A_DIFFERENT_VTS;
        }

        $reTest = $this->motTestRepository->findRetestForMotTest($lastTest->getNumber());
        if ($reTest instanceof MotTest) {
            $resultCodes [] = RetestEligibilityCheckCode::RETEST_REJECTED_ALREADY_REGISTERED;
        }
        return $resultCodes;
    }

    /**
     * @param MotTest $test
     * @param $contingencyDto
     * @return bool
     *
     * @throws NotFoundException
     */
    private function isWithinTenWorkingDays(MotTest $test, $contingencyDto = null)
    {
        $country = $test->getVehicleTestingStation()->getNonWorkingDayCountry();
        if (is_null($country)) {
            throw new NotFoundException("Vts country required");
        }

        $lastTestCompletedDate = DU::cropTime($test->getCompletedDate());
        $queryDate = ($contingencyDto instanceof ContingencyMotTestDto
            ? DU::cropTime(new \DateTime($contingencyDto->getPerformedAt() . ' 00:00:00'))
            : $this->dateTime->getCurrentDate()
        );

        $countryCode = $country->getCountry()->getCode();
        $nthWorkingDayDate = $this->nonWorkingDaysHelper->calculateNthWorkingDayAfter(
            $lastTestCompletedDate,
            self::RETEST_WORKING_DAYS_PERIOD,
            $countryCode
        );
        $dayDiff = intval(DU::getDaysDifference($nthWorkingDayDate, $queryDate));
        return $dayDiff <= 0;

    }

    public function setDateTimeHolder(DateTimeHolder $dateTimeHolder)
    {
        $this->dateTime = $dateTimeHolder;
        return $this;
    }
}
