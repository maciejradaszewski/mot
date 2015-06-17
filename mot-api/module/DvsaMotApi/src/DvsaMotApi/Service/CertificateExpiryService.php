<?php

namespace DvsaMotApi\Service;

use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Date\DateTimeHolder;
use DvsaCommon\Date\DateUtils;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaEntities\Repository\ConfigurationRepository;
use DvsaEntities\Repository\MotTestRepository;
use DvsaEntities\Repository\VehicleRepository;
use DvsaEntities\Repository\DvlaVehicleRepository;

/**
 * Contains services related to certificate expiry feature.
 */
class CertificateExpiryService
{
    const CALENDAR_MONTHS_ALLOWED_TO_POST_DATE = 'maxCalendarMthsForPostdatingExpiryDte';
    const YEARS_BEFORE_FIRST_TEST_IS_DUE = 'yearsBeforeFirstMotTestIsDue';

    /** @var \DvsaCommon\Date\DateTimeHolder */
    private $dateTime;
    /** @var DvlaVehicleRepository $dvlaVehicleRepository */
    private $dvlaVehicleRepository;
    /** @var MotTestRepository $motTestRepository */
    private $motTestRepository;
    /** @var VehicleRepository $vehicleRepository */
    private $vehicleRepository;
    /** @var ConfigurationRepository */
    private $configurationRepository;
    /** @var AuthorisationServiceInterface $authService */
    private $authService;


    /**
     * @param DateTimeHolder $dateTimeHolder
     * @param MotTestRepository $motTestRepository
     * @param VehicleRepository $vehicleRepository
     * @param DvlaVehicleRepository $dvlaVehicleRepository
     * @param ConfigurationRepository $configurationRepository
     */
    public function __construct(
        DateTimeHolder $dateTimeHolder,
        MotTestRepository $motTestRepository,
        VehicleRepository $vehicleRepository,
        DvlaVehicleRepository $dvlaVehicleRepository,
        ConfigurationRepository $configurationRepository,
        AuthorisationServiceInterface $authService
    ) {
        $this->dateTime = $dateTimeHolder;
        $this->motTestRepository = $motTestRepository;
        $this->vehicleRepository = $vehicleRepository;
        $this->dvlaVehicleRepository = $dvlaVehicleRepository;
        $this->configurationRepository = $configurationRepository;
        $this->authService = $authService;
    }

    /**
     * Method retrieves the previous certificate expiry data
     * for a vehicle.
     *
     * @param string              $vehicleId Database identifier of vehicle
     * @param bool                $isDvla    Search in the DVLA vehicle table if true or the standard one if false
     * @param DateTimeHolder|null $testDate  Date of the test
     *
     * @throws NotFoundException If the vehicle cannot be found
     *
     * @return array
     *      previousCertificateExists                   Boolean
     *      expiryDate                                  Date
     *      earliestTestDateForPostdatingExpiryDate     Date
     *      $isEarlierThanTestDateLimit                 Boolean
     */
    public function getExpiryDetailsForVehicle($vehicleId, $isDvla = false, $testDate = null)
    {
        $this->authService->assertGranted(PermissionInSystem::TESTER_READ);

        $checkExpiryResults = [];
        if ($testDate === null) {
            $testDate = $this->dateTime->getCurrentDate();
        }

        $expiryDate = $this->motTestRepository->findLastCertificateExpiryDate($vehicleId);

        if (!$expiryDate) {
            // Use a notional expiry date based on the first use date instead.
            $vehicle = (
                true === $isDvla
                ? $this->dvlaVehicleRepository->find($vehicleId)
                : $this->vehicleRepository->find($vehicleId)
            );
            if (!$vehicle) {
                throw new NotFoundException($isDvla ? 'DvlaVehicle' : 'Vehicle');
            }

            $firstUsedDate = $vehicle->getFirstUsedDate();

            $expiryDate = $this->getNotionalPreviousExpiryDateForFirstCertificate($firstUsedDate);
            $checkExpiryResults['previousCertificateExists'] = false;
        } else {
            $checkExpiryResults['previousCertificateExists'] = true;
        }

        if ($expiryDate) {
            $earliestTestDateForPostdatingExpiryDate = $this->getEarliestTestDateForPostdatingExpiryDate($expiryDate);
            $isEarlierThanTestDateLimit = $testDate < $earliestTestDateForPostdatingExpiryDate;

            $checkExpiryResults['expiryDate'] = DateTimeApiFormat::date($expiryDate);
            $checkExpiryResults['earliestTestDateForPostdatingExpiryDate']
                = DateTimeApiFormat::date($earliestTestDateForPostdatingExpiryDate);
            $checkExpiryResults['isEarlierThanTestDateLimit'] = $isEarlierThanTestDateLimit;
        }

        return $checkExpiryResults;
    }

    /**
     * Retrieves the earliest test date for postdating expiry date.
     *
     * @param $expiryDate
     *
     * @return \DateTime
     */
    private function getEarliestTestDateForPostdatingExpiryDate($expiryDate)
    {
        return DateUtils::preservationDate($expiryDate);
    }

    /**
     * Returns the notional previous expiry date for a first certificate based on first use date.
     *
     * @param \DateTime $firstUsedDate
     *
     * @return \DateTime
     */
    private function getNotionalPreviousExpiryDateForFirstCertificate(\DateTime $firstUsedDate)
    {
        $prevExpiryDate = clone $firstUsedDate;

        $years = (int)$this->configurationRepository->getValue(self::YEARS_BEFORE_FIRST_TEST_IS_DUE);
        return $prevExpiryDate->add(new \DateInterval('P' . $years . 'Y'))
            ->sub(new \DateInterval('P1D'));
    }
}
