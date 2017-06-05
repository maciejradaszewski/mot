<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link https://gitlab.motdev.org.uk/mot
 */

namespace DvsaMotApi\Service;

use Doctrine\ORM\EntityManager;
use Dvsa\Mot\ApiClient\Resource\Item\DvsaVehicle;
use Dvsa\Mot\ApiClient\Service\VehicleService as NewVehicleService;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Constants\Network;
use DvsaCommon\Dto\MotTesting\ContingencyTestDto;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommon\Utility\DtoHydrator;
use DvsaCommonApi\Transaction\TransactionAwareInterface;
use DvsaCommonApi\Transaction\TransactionAwareTrait;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Entity\MotTestComplaintRef;
use DvsaEntities\Entity\Person;
use DvsaEntities\Repository\MotTestRepository;
use DvsaEntities\Repository\PersonRepository;
use DvsaMotApi\Controller\Validator\CreateMotTestRequestValidator;
use DvsaMotApi\Helper\MysteryShopperHelper;
use DvsaMotApi\Service\Validator\MotTestValidator;
use DvsaMotApi\Service\Validator\RetestEligibility\RetestEligibilityValidator;
use OrganisationApi\Service\OrganisationService;
use VehicleApi\Service\VehicleService as VehicleApiVehicleService;

class CreateMotTestService implements TransactionAwareInterface
{
    use TransactionAwareTrait;

    const FIELD_VEHICLE_ID = 'vehicleId';
    const FIELD_DVLA_VEHICLE_ID = 'dvlaVehicleId';
    const FIELD_VTS_ID = 'vehicleTestingStationId';
    const FIELD_HAS_REGISTRATION = 'hasRegistration';
    const FIELD_MOT_TEST_TYPE = 'motTestType';
    const FIELD_MOT_TEST_NUMBER_ORIGINAL = 'motTestNumberOriginal';
    const FIELD_ODOMETER_READING = 'odometerReading';
    const FIELD_MOT_TEST_COMPLAINT_REF = 'complaintRef';
    const FIELD_COLOURS = 'colours';
    const FIELD_COLOURS_PRIMARY = 'primaryColour';
    const FIELD_COLOURS_SECONDARY = 'secondaryColour';
    const FIELD_COUNTRY_OF_REGISTRATION = 'countryOfRegistration';
    const FIELD_FUEL_TYPE_CODE = 'fuelTypeId';
    const FIELD_CYLINDER_CAPACITY = 'cylinderCapacity';
    const FIELD_VEHICLE_MAKE = 'vehicleMake';
    const FIELD_VEHICLE_MODEL = 'vehicleModel';
    const FIELD_VEHICLE_CLASS_CODE = 'vehicleClassCode';
    const FIELD_REASON_DIFFERENT_TESTER_CODE = 'differentTesterReasonCode';
    const FIELD_SITEID = 'siteid';
    const FIELD_LOCATION = 'location';
    const FIELD_FLAG_PRIVATE = 'flagPrivate';
    const FIELD_ONE_PERSON_TEST = 'onePersonTest';
    const FIELD_ONE_PERSON_RE_INSPECTION = 'onePersonReInspection';
    const FIELD_ONE_TIME_PASSWORD = 'oneTimePassword';
    const FIELD_CONTINGENCY = 'contingencyId';
    const FIELD_CONTINGENCY_DTO = 'contingencyDto';
    const FIELD_CLIENT_IP = 'clientIp';

    /** @var EntityManager */
    private $entityManager;
    /** @var MotTestRepository */
    private $motTestRepository;
    /** @var MotTestValidator */
    private $motTestValidator;
    /** @var AuthorisationServiceInterface */
    private $authService;
    /** @var TesterService */
    private $testerService;
    /** @var RetestEligibilityValidator */
    private $retestEligibilityValidator;

    /** @var OrganisationService $organisationService */
    private $organisationService;

    /** @var VehicleApiVehicleService */
    private $vehicleService;

    /** @var NewVehicleService */
    private $newVehicleService;

    /** @var MotIdentityProviderInterface */
    private $identityProvider;

    /** @var PersonRepository */
    private $personRepository;

    /**
     * @var MysteryShopperHelper
     */
    private $mysteryShopperHelper;

    /**
     * CreateMotTestService constructor.
     *
     * @param EntityManager                 $entityManager
     * @param MotTestValidator              $motTestValidator
     * @param AuthorisationServiceInterface $authService
     * @param TesterService                 $testerService
     * @param RetestEligibilityValidator    $retestEligibilityValidator
     * @param OrganisationService           $organisationService
     * @param VehicleApiVehicleService      $vehicleService
     * @param MotIdentityProviderInterface  $identityProvider
     * @param NewVehicleService             $newVehicleService
     * @param PersonRepository              $personRepository
     * @param MotTestRepository             $motTestRepository
     * @param MysteryShopperHelper          $mysteryShopperHelper
     */
    public function __construct(
        EntityManager $entityManager,
        MotTestValidator $motTestValidator,
        AuthorisationServiceInterface $authService,
        TesterService $testerService,
        RetestEligibilityValidator $retestEligibilityValidator,
        OrganisationService $organisationService,
        VehicleApiVehicleService $vehicleService,
        MotIdentityProviderInterface $identityProvider,
        NewVehicleService $newVehicleService,
        PersonRepository $personRepository,
        MotTestRepository $motTestRepository,
        MysteryShopperHelper $mysteryShopperHelper
    ) {
        $this->entityManager = $entityManager;
        $this->motTestValidator = $motTestValidator;
        $this->authService = $authService;
        $this->testerService = $testerService;
        $this->retestEligibilityValidator = $retestEligibilityValidator;
        $this->organisationService = $organisationService;
        $this->vehicleService = $vehicleService;
        $this->newVehicleService = $newVehicleService;
        $this->identityProvider = $identityProvider;
        $this->motTestRepository = $motTestRepository;
        $this->personRepository = $personRepository;
        $this->mysteryShopperHelper = $mysteryShopperHelper;
    }

    /**
     * @param array $data
     *
     * @return MotTest
     */
    public function create(array $data)
    {
        CreateMotTestRequestValidator::validate($data);
        $userId = $this->identityProvider->getIdentity()->getUserId();
        $person = $this->personRepository->get($userId);

        $dvlaVehicleId = ArrayUtils::tryGet($data, self::FIELD_DVLA_VEHICLE_ID);
        $vehicleId = ArrayUtils::tryGet($data, self::FIELD_VEHICLE_ID);

        // Unless a new siteId has been specified (for a reinspection) we want to maintain the old value...
        $vehicleTestingStationId = is_null($data[self::FIELD_VTS_ID]) ? null : (int) $data[self::FIELD_VTS_ID];
        $primaryColour = ArrayUtils::tryGet($data, self::FIELD_COLOURS_PRIMARY);
        $secondaryColour = ArrayUtils::tryGet($data, self::FIELD_COLOURS_SECONDARY);
        $fuelTypeCode = ArrayUtils::tryGet($data, self::FIELD_FUEL_TYPE_CODE);
        $cylinderCapacity = ArrayUtils::tryGet($data, self::FIELD_CYLINDER_CAPACITY);
        $vehicleMake = ArrayUtils::tryGet($data, self::FIELD_VEHICLE_MAKE);
        $countryOfRegistration = ArrayUtils::tryGet($data, self::FIELD_COUNTRY_OF_REGISTRATION);
        $vehicleModel = ArrayUtils::tryGet($data, self::FIELD_VEHICLE_MODEL);
        $vehicleClassCode = ArrayUtils::tryGet($data, self::FIELD_VEHICLE_CLASS_CODE);
        $hasRegistration = ArrayUtils::tryGet($data, self::FIELD_HAS_REGISTRATION, false);
        $motTestNumberOriginal = ArrayUtils::tryGet($data, self::FIELD_MOT_TEST_NUMBER_ORIGINAL);
        $complaintRef = ArrayUtils::tryGet($data, self::FIELD_MOT_TEST_COMPLAINT_REF);
        $motTestTypeCode = ArrayUtils::tryGet($data, self::FIELD_MOT_TEST_TYPE, MotTestTypeCode::NORMAL_TEST);
        $contingencyId = ArrayUtils::tryGet($data, self::FIELD_CONTINGENCY);
        $contingencyDto = ArrayUtils::tryGet($data, self::FIELD_CONTINGENCY_DTO);
        $clientIp = ArrayUtils::tryGet($data, self::FIELD_CLIENT_IP, Network::DEFAULT_CLIENT_IP);

        if (MotTestTypeCode::NORMAL_TEST === $motTestTypeCode
            && $this->mysteryShopperHelper->isVehicleMysteryShopper($vehicleId)
        ) {
            $motTestTypeCode = MotTestTypeCode::MYSTERY_SHOPPER;
        }

        if (!is_null($contingencyDto)) {
            $contingencyDto = DtoHydrator::jsonToDto($contingencyDto);
        }

        if (!empty($complaintRef)) {
            $motTestComplaintRef = new MotTestComplaintRef();
            $motTestComplaintRef->setComplaintRef($complaintRef);
        } else {
            $motTestComplaintRef = null;
        }

        /** @var MotTest $motTest */
        $motTest = $this->save(
            $person,
            $vehicleId,
            $vehicleTestingStationId,
            $primaryColour,
            $secondaryColour,
            $fuelTypeCode,
            $cylinderCapacity,
            $vehicleMake,
            $vehicleModel,
            $vehicleClassCode,
            $countryOfRegistration,
            $hasRegistration,
            $dvlaVehicleId,
            $motTestTypeCode,
            $clientIp,
            $motTestNumberOriginal,
            $motTestComplaintRef,
            $contingencyId,
            $contingencyDto
        );

        /*
         * Update the slot count in a separate transaction to fix VM-3254
         */
        $this->updateSlotCount($motTest);

        return $motTest;
    }

    /**
     * @param Person                   $tester
     * @param                          $vehicleId
     * @param                          $vehicleTestingStationId
     * @param                          $primaryColourCode
     * @param                          $secondaryColourCode
     * @param                          $fuelTypeCode
     * @param                          $cylinderCapacity
     * @param                          $vehicleMake
     * @param                          $vehicleModel
     * @param                          $vehicleClassCode
     * @param                          $countryOfRegistrationId
     * @param                          $hasRegistration
     * @param                          $dvlaVehicleId
     * @param                          $motTestTypeCode
     * @param                          $clientIp
     * @param null                     $motTestNumberOriginal
     * @param MotTestComplaintRef|null $complaintRef
     * @param null                     $contingencyId
     * @param ContingencyTestDto|null  $contingencyDto
     *
     * @return mixed
     */
    private function save(
        Person $tester,
        $vehicleId,
        $vehicleTestingStationId,
        $primaryColourCode,
        $secondaryColourCode,
        $fuelTypeCode,
        $cylinderCapacity,
        $vehicleMake,
        $vehicleModel,
        $vehicleClassCode,
        $countryOfRegistrationId,
        $hasRegistration,
        $dvlaVehicleId,
        $motTestTypeCode,
        $clientIp,
        $motTestNumberOriginal = null,
        MotTestComplaintRef $complaintRef = null,
        $contingencyId = null,
        ContingencyTestDto $contingencyDto = null
    ) {
        if ((int) $dvlaVehicleId > 0) {
            $vehicleId = $this->vehicleService->getVehicleIdIfAlreadyImportedFromDvla($dvlaVehicleId);
            if (!$vehicleId) {
                $vehicle = $this->vehicleService->createVtrAndV5CFromDvlaVehicle($dvlaVehicleId, $vehicleClassCode);

                if (!$vehicle instanceof DvsaVehicle) {
                    throw new \RuntimeException(
                        sprintf(
                            'We have failed to import a dvla vehicle, attempted id: %s and vehicle class code: %s',
                            $dvlaVehicleId,
                            $vehicleClassCode
                        )
                    );
                }

                $vehicleId = $vehicle->getId();

                $this->vehicleService->logDvlaVehicleImportChanges(
                    $tester,
                    $vehicle,
                    $vehicleClassCode,
                    $primaryColourCode,
                    $secondaryColourCode,
                    $fuelTypeCode
                );
            }
        }

        if (!isset($vehicleId)) {
            throw new \RuntimeException('At this point we should have a vehicle id');
        }

        $motTestCreationHelper = (new MotTestCreationHelper(
            $this->entityManager,
            $this->authService,
            $this->testerService,
            $this->motTestRepository,
            $this->motTestValidator,
            $this->retestEligibilityValidator,
            $this->identityProvider,
            $this->newVehicleService
        ));
        $motTestCreationHelper->updateVehicleIfChanged(
            $fuelTypeCode,
            $cylinderCapacity,
            $vehicleMake,
            $vehicleModel,
            $vehicleId,
            $vehicleClassCode,
            $fuelTypeCode,
            $primaryColourCode,
            $secondaryColourCode,
            $countryOfRegistrationId,
            $motTestTypeCode
        );

        return $this->inTransaction(
            function () use (
                $tester,
                $vehicleId,
                $vehicleTestingStationId,
                $vehicleClassCode,
                $countryOfRegistrationId,
                $hasRegistration,
                $dvlaVehicleId,
                $motTestTypeCode,
                $motTestNumberOriginal,
                $complaintRef,
                $contingencyId,
                $contingencyDto,
                $clientIp,
                $motTestCreationHelper
            ) {
                return $motTestCreationHelper->createMotTest(
                    $tester,
                    $vehicleId,
                    $vehicleTestingStationId,
                    $vehicleClassCode,
                    $hasRegistration,
                    $motTestTypeCode,
                    $motTestNumberOriginal,
                    $clientIp,
                    $contingencyId,
                    $contingencyDto,
                    $complaintRef
                );
            }
        );
    }

    /**
     * @param MotTest $motTest
     */
    private function updateSlotCount(MotTest $motTest)
    {
        if ($motTest->getMotTestType()->getIsSlotConsuming()) {
            $this->inTransaction(
                function () use ($motTest) {
                    $this->organisationService->decrementSlotBalance(
                        $motTest->getVehicleTestingStation()->getOrganisation()
                    );
                }
            );
        }
    }
}
