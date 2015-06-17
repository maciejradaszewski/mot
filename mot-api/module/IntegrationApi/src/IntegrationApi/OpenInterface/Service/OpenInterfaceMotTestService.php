<?php

namespace IntegrationApi\OpenInterface\Service;

use Doctrine\Common\Collections\Criteria;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaCommonApi\Service\Exception\ServiceException;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Entity\Vehicle;
use IntegrationApi\OpenInterface\Mapper\OpenInterfaceMotTestMapper;
use IntegrationApi\OpenInterface\Repository\OpenInterfaceMotTestRepository;
use IntegrationApi\OpenInterface\Validator\OpenInterfaceMotTestRequestValidator;

/**
 * Class OpenInterfaceMotTestService.
 */
class OpenInterfaceMotTestService
{
    private $repository;
    private $mapper;

    const PRE_1960_DATE = 1960;

    /**
     * @param OpenInterfaceMotTestRepository $repository
     */
    public function __construct(OpenInterfaceMotTestRepository $repository)
    {
        $this->repository = $repository;
        $this->mapper = new OpenInterfaceMotTestMapper();
    }

    /**
     * Returns data of a Passed test with latest in the future expiry date.
     * Test was issued before or on given day (today if no date specified).
     * Throws 404 if only Failed tests found or no tests found at all.
     *
     * @param $vrm - vehicle registration mark
     * @param $issuedBeforeString - latest possible date of the issue given in YYYYMMDD format
     *
     * @return array - MOT Test data
     * @throws \DvsaCommonApi\Service\Exception\ServiceException when Pass MOT test not found
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException when none (Pass or Fail MOT) test found at all
     */
    public function getPassMotTestForVehicleIssuedBefore($vrm, $issuedBeforeString = null)
    {
        if (is_null($issuedBeforeString)) {
            $issuedBefore = new \DateTime();
        } else {
            $issuedBefore = $this->toDateTimeObject($issuedBeforeString);
        }

        $passedMotTest = $this->repository->findLatestMotTestForVrm(
            $vrm, $issuedBefore, MotTestStatusName::PASSED, [
                MotTestTypeCode::DEMONSTRATION_TEST_FOLLOWING_TRAINING,
                MotTestTypeCode::ROUTINE_DEMONSTRATION_TEST,
                MotTestTypeCode::TARGETED_REINSPECTION,
                MotTestTypeCode::MOT_COMPLIANCE_SURVEY
            ]
        );
        
        if ($passedMotTest == null || $passedMotTest->isExpired()) {
            $vehicle = $this->repository->findVehicleByVrm($vrm);

            if(!is_a($vehicle, Vehicle::class)) {
                throw new NotFoundException("Vehicle with VRM: " . $vrm);
            }

            if ($this->isVehiclePre1960($vehicle)) {
                return $this->mapper->pre1960VehicleWithNoMotTestToArray($vehicle);
            }
        }

        if ($passedMotTest) {
            return $this->mapper->toArray($passedMotTest);
        }

        $failedMotTest = $this->repository->findLatestMotTestForVrm(
            $vrm, $issuedBefore, MotTestStatusName::FAILED, [
                MotTestTypeCode::DEMONSTRATION_TEST_FOLLOWING_TRAINING,
                MotTestTypeCode::ROUTINE_DEMONSTRATION_TEST,
                MotTestTypeCode::TARGETED_REINSPECTION,
                MotTestTypeCode::MOT_COMPLIANCE_SURVEY
            ]
        );

        if ($failedMotTest) {
            $noPassException = new ServiceException(null, NotFoundException::ERROR_CODE_NOT_FOUND);
            $noPassException->addError("Pass MOT test not found; Fail found", "NO_PASS", null);

            throw $noPassException;
        }

        throw new NotFoundException("MOT test");
    }

    private function isVehiclePre1960(Vehicle $vehicle)
    {
        return ($vehicle->getManufactureDate() && $vehicle->getManufactureDate()->format('Y') < self::PRE_1960_DATE)
        || ($vehicle->getFirstRegistrationDate() && $vehicle->getFirstRegistrationDate()->format('Y') < self::PRE_1960_DATE);
    }

    private function toDateTimeObject($dateString)
    {
        $validator = new OpenInterfaceMotTestRequestValidator();
        $validator->validateDate($dateString);

        return \DateTime::createFromFormat('Ymd', $dateString);
    }
}
