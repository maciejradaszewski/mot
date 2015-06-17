<?php

namespace DvsaMotApi\Service;

use DvsaCommon\Date\DateTimeHolder;
use DvsaCommon\Date\DateUtils;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Entity\Vehicle;

/**
 * Class MotTestDateHelper
 *
 * @package DvsaMotApi\Service
 */
class MotTestDateHelper
{

    private $certificateExpiryService;
    private $dateTimeHolder;

    public function __construct(
        CertificateExpiryService $certificateExpiryService
    ) {
        $this->certificateExpiryService = $certificateExpiryService;
        $this->dateTimeHolder = new DateTimeHolder();
    }

    public function getIssuedDate(MotTest $motTest, $issuedDate = null, $pendingStatus = null)
    {
        //  --  define issued date  --
        if ($issuedDate === null) {
            if ($motTest->getEmergencyLog() === null) {
                $issuedDate = $this->dateTimeHolder->getCurrent();
            } else {
                $issuedDate = $motTest->getStartedDate();
            }
        }

        //  --  set or not issued date to mot test in depend from test type and test status --
        $isTestIncomplete = ($pendingStatus == MotTestService::PENDING_INCOMPLETE_STATUS);

        $hasDate = !$isTestIncomplete;

        return ($hasDate ? $issuedDate : null);
    }

    /**
     * @param MotTest $motTest
     * @param null    $issuedDate
     * @param null    $pendingStatus
     *
     * @throws \Exception
     * @return \DateTime|null
     */
    public function getExpiryDate(MotTest $motTest, $issuedDate = null, $pendingStatus = null)
    {
        //  --  define, should date be set by business logic    --
        $isTestPassed = $motTest->isPassed() || $pendingStatus == MotTestStatusName::PASSED;

        $motTestTypeCode = $motTest->getMotTestType()->getCode();

        $isNeedSetDate = $isTestPassed
            && !($motTestTypeCode === MotTestTypeCode::MOT_COMPLIANCE_SURVEY
                || $motTestTypeCode === MotTestTypeCode::TARGETED_REINSPECTION
            );

        if (!$isNeedSetDate) {
            return null;
        }

        //  --  define issued date  --
        if ($issuedDate === null) {
            $issuedDate = $motTest->getIssuedDate();
        }
        if (!$issuedDate instanceof \DateTime) {
            throw new \Exception("Issued date must be supplied or found from motTest");
        }

        $issuedDate = DateUtils::cropTime($issuedDate);

        //  --  define expiry date base on previous certificate of vehicle --
        $vehicle = $motTest->getVehicle();
        if ($vehicle instanceof Vehicle && ((int)$vehicle->getId()) > 0) {
            $checkResult = $this->certificateExpiryService->getExpiryDetailsForVehicle(
                $vehicle->getId(),
                $motTest->getEmergencyLog() !== null ?
                $motTest->getStartedDate() : $this->dateTimeHolder->getCurrentDate()
            );
            if ($checkResult) {
                $isEarlierThanTestDateLimit = $checkResult['isEarlierThanTestDateLimit'];

                $previousExpiryDate = DateUtils::toDate($checkResult['expiryDate']);

                if (!$isEarlierThanTestDateLimit && $issuedDate < $previousExpiryDate) {
                    //Test expiry date can be postdated.
                    return $this->getNewExpiryDate($previousExpiryDate->add(new \DateInterval('P1D')));
                }
            }
        }

        return $this->getNewExpiryDate($issuedDate);
    }

    protected function getNewExpiryDate(\DateTime $issueDate)
    {
        $expiryDate = clone $issueDate;

        $expiryDate
            ->add(new \DateInterval('P1Y'))
            ->sub(new \DateInterval('P1D'));

        return $expiryDate;
    }
}
