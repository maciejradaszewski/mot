<?php

namespace VehicleApi\Service;

use DataCatalogApi\Service\VehicleCatalogService;
use Doctrine\ORM\EntityRepository;
use DvsaAuthentication\Service\OtpService;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Database\Transaction;
use DvsaCommon\Date\DateUtils;
use DvsaCommon\Dto\Vehicle\VehicleCreatedDto;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommon\Enum\WeightSourceCode;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaCommon\Utility\ArrayUtils;
use DvsaEntities\Entity\DvlaVehicle;
use DvsaEntities\Entity\DvlaVehicleImportChangeLog;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\Vehicle;
use DvsaEntities\Entity\VehicleV5C;
use DvsaEntities\Repository\DvlaVehicleImportChangesRepository;
use DvsaEntities\Repository\DvlaVehicleRepository;
use DvsaEntities\Repository\PersonRepository;
use DvsaEntities\Repository\VehicleRepository;
use DvsaEntities\Repository\VehicleV5CRepository;
use DvsaMotApi\Service\CreateMotTestService;
use DvsaMotApi\Service\MotTestServiceProvider;
use DvsaMotApi\Service\Validator\VehicleValidator;
use VehicleApi\Service\Mapper\DvlaVehicleMapper;
use VehicleApi\Service\Mapper\VehicleMapper;

/**
 * Class VehicleService.
 */
class VehicleService
{
    const DEFAULT_COUNTRY_OF_REGISTRATION = 'GB';
    const DEFAULT_MAKE_MODEL_NAME = 'Unknown';
    const DEFAULT_BODY_TYPE_CODE = '0';

    /** @var  AuthorisationServiceInterface */
    private $authService;
    /** @var  VehicleRepository */
    private $vehicleRepository;
    /** @var  DvlaVehicleRepository */
    private $dvlaVehicleRepository;
    /** @var DvlaVehicleImportChangesRepository */
    private $dvlaVehicleImportChangesRepository;
    /** @var EntityRepository */
    private $dvlaMakeModelMapRepository;
    /** @var VehicleV5CRepository */
    private $vehicleV5CRepository;
    /** @var VehicleCatalogService */
    private $vehicleCatalog;
    /** @var  VehicleValidator */
    private $validator;
    /** @var  OtpService */
    private $otpService;

    private $motTestServiceProvider;

    private $identityProvider;

    private $personRepository;

    private $transaction;

    public function __construct(
        AuthorisationServiceInterface $authService,
        VehicleRepository $repository,
        VehicleV5CRepository $vehicleV5CRepository,
        DvlaVehicleRepository $dvlaVehicleRepository,
        DvlaVehicleImportChangesRepository $dvlaVehicleImportChangesRepository,
        EntityRepository $dvlaMakeModelMapRepository,
        VehicleCatalogService $vehicleCatalog,
        VehicleValidator $validator,
        OtpService $otpService,
        ParamObfuscator $paramObfuscator,
        MotTestServiceProvider $motTestServiceProvider,
        MotIdentityProviderInterface $identityProvider,
        PersonRepository $personRepository,
        Transaction $transaction
    ) {
        $this->authService = $authService;
        $this->vehicleRepository = $repository;
        $this->vehicleV5CRepository = $vehicleV5CRepository;
        $this->dvlaVehicleRepository = $dvlaVehicleRepository;
        $this->dvlaVehicleImportChangesRepository =
            $dvlaVehicleImportChangesRepository;
        $this->dvlaMakeModelMapRepository = $dvlaMakeModelMapRepository;
        $this->vehicleCatalog = $vehicleCatalog;
        $this->validator = $validator;
        $this->otpService = $otpService;
        $this->paramObfuscator = $paramObfuscator;
        $this->motTestServiceProvider = $motTestServiceProvider;
        $this->identityProvider = $identityProvider;
        $this->personRepository = $personRepository;
        $this->transaction = $transaction;
    }

    public function create($data)
    {
        $this->authService->assertGranted(PermissionInSystem::VEHICLE_CREATE);
        $this->validator->validate($data);

        if (!$this->authService->isGranted(PermissionInSystem::MOT_TEST_WITHOUT_OTP)) {
            $token = ArrayUtils::tryGet($data, 'oneTimePassword');
            $this->otpService->authenticate($token);
        }

        $vehicle = $this->mapVehicle($data);

        $this->transaction->begin();

        try {
            $this->vehicleRepository->save($vehicle);

            $motTest = $this->startMotTest($data, $vehicle->getId());

            $this->transaction->commit();
            $dto = new VehicleCreatedDto();
            $dto->setStartedMotTestNumber($motTest->getNumber());

            if (isset($data['returnOriginalId'])) {
                $dto->setVehicleId($vehicle->getId());
            } else {
                $dto->setVehicleId(
                    $this->paramObfuscator->obfuscateEntry(ParamObfuscator::ENTRY_VEHICLE_ID, $vehicle->getId())
                );
            }

            return $dto;
        } catch (\Exception $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }

    /**
     * @param array $data
     * @param int $vehicleId
     * @return \DvsaEntities\Entity\MotTest
     */
    private function startMotTest(array $data, $vehicleId)
    {
        $motTestData = [];
        $motTestData[CreateMotTestService::FIELD_VEHICLE_ID] = $vehicleId;
        $motTestData[CreateMotTestService::FIELD_VTS_ID] = ArrayUtils::tryGet($data, 'vtsId');
        $motTestData[CreateMotTestService::FIELD_HAS_REGISTRATION] = true;
        $motTestData[CreateMotTestService::FIELD_COLOURS_PRIMARY] = ArrayUtils::tryGet($data, 'colour');
        $motTestData[CreateMotTestService::FIELD_COLOURS_SECONDARY] = ArrayUtils::tryGet($data, 'secondaryColour');
        $motTestData[CreateMotTestService::FIELD_VEHICLE_CLASS_CODE] = ArrayUtils::tryGet($data, 'testClass');
        $motTestData[CreateMotTestService::FIELD_MOT_TEST_TYPE] = MotTestTypeCode::NORMAL_TEST;
        $motTestData[CreateMotTestService::FIELD_FUEL_TYPE_ID] = ArrayUtils::tryGet($data, 'fuelType');
        $motTestData[CreateMotTestService::FIELD_ONE_TIME_PASSWORD] = ArrayUtils::tryGet($data, 'oneTimePassword');
        $motTestData[CreateMotTestService::FIELD_CLIENT_IP] = ArrayUtils::tryGet($data, 'clientIp');

        // Contingency Data
        $motTestData[CreateMotTestService::FIELD_CONTINGENCY] = ArrayUtils::tryGet(
            $data,
            CreateMotTestService::FIELD_CONTINGENCY
        );

        $motTestData[CreateMotTestService::FIELD_CONTINGENCY_DTO] = ArrayUtils::tryGet(
            $data,
            CreateMotTestService::FIELD_CONTINGENCY_DTO
        );

        return $this->motTestServiceProvider->getService()->createMotTest($motTestData);
    }

    public function getVehicle($id)
    {
        $this->authService->assertGranted(PermissionInSystem::VEHICLE_READ);

        return $this->vehicleRepository->get($id);
    }

    private function getDvlaVehicle($id)
    {
        $this->authService->assertGranted(PermissionInSystem::VEHICLE_READ);

        return $this->dvlaVehicleRepository->get($id);
    }

    /**
     * @param $vehicleId
     *
     * @return \DvsaCommon\Dto\Vehicle\VehicleDto
     */
    public function getVehicleDto($vehicleId)
    {
        $v = $this->getVehicle($vehicleId);
        $m = (new VehicleMapper())->toDto($v)
            ->setId($vehicleId);

        return $m;
    }

    public function getDvlaVehicleData($vehicleId)
    {
        $dvlaVehicleMapper = new DvlaVehicleMapper($this->vehicleCatalog);

        return $dvlaVehicleMapper->toDto($this->getDvlaVehicle($vehicleId))
            ->setId($vehicleId);
    }

    /**
     * @param $dvlaVehicleId
     * @param $vehicleClassCode
     *
     * @return Vehicle
     */
    public function createVtrAndV5CFromDvlaVehicle($dvlaVehicleId, $vehicleClassCode)
    {
        $this->authService->assertGranted(PermissionInSystem::VEHICLE_READ);
        $this->authService->assertGranted(PermissionInSystem::VEHICLE_CREATE);

        $dvlaVehicle = $this->dvlaVehicleRepository->get($dvlaVehicleId);

        $fuelType = $this->vehicleCatalog->findFuelTypeByPropulsionCode($dvlaVehicle->getFuelType());

        $makeCode = $dvlaVehicle->getMakeCode();
        $modelCode = $dvlaVehicle->getModelCode();
        $makeName = null;
        $modelName = null;
        $map = null;
        $make = null;
        $model = null;

        // Logic
        // When DVLA populate the MakeInFull value, the dvla make code always maps to UNKNOWN,
        // and the model code maps to UNKNOWN also
        if (!$dvlaVehicle->getMakeInFull()) {
            if (!is_null($makeCode) || !is_null($modelCode)) {
                $map = $this->vehicleCatalog->getMakeModelMapByDvlaCode($makeCode, $modelCode);

                $make = $map ? $map->getMake() : null;
                $model = $map ? $map->getModel() : null;

                if ($map) {
                    $makeName  = (!$map->getMake())  ? null : $map->getMake()->getName();
                    $modelName = (!$map->getModel()) ? null : $map->getModel()->getName();
                }

                if (is_null($makeName)) {
                    $makeName = $this->vehicleCatalog->getMakeNameByDvlaCode($makeCode);
                    if (!$makeName) {
                        $makeName = self::DEFAULT_MAKE_MODEL_NAME;
                    }
               }

               if (is_null($modelName)) {
                   $modelName = $this->vehicleCatalog->getModelNameByDvlaCode($makeCode, $modelCode);
               }

               if (empty($modelName)) {
                    $modelName = null;
               }
            } else {
                $makeName = self::DEFAULT_MAKE_MODEL_NAME;
            }
        } else {
            $makeName = $dvlaVehicle->getMakeInFull();
        }

        $bodyType = $this->vehicleCatalog->findBodyTypeByCode($dvlaVehicle->getBodyType());

        if (is_null($bodyType)) {
            $bodyType = $this->vehicleCatalog->findBodyTypeByCode(self::DEFAULT_BODY_TYPE_CODE);
        }

        $vehicle = (new Vehicle())
            ->setVin($dvlaVehicle->getVin())
            ->setRegistration($dvlaVehicle->getRegistration())
            ->setManufactureDate($dvlaVehicle->getManufactureDate())
            ->setFirstRegistrationDate($dvlaVehicle->getFirstRegistrationDate())
            ->setFirstUsedDate($dvlaVehicle->getFirstUsedDate())
            ->setColour($this->vehicleCatalog->getColourByCode($dvlaVehicle->getPrimaryColour()))
            ->setBodyType($bodyType)
            ->setCylinderCapacity($dvlaVehicle->getCylinderCapacity())
            ->setVehicleClass($this->vehicleCatalog->getVehicleClassByCode($vehicleClassCode))
            ->setMake($make)
            ->setModel($model)
            ->setCountryOfRegistration(
                $this->vehicleCatalog->getCountryOfRegistrationByCode(self::DEFAULT_COUNTRY_OF_REGISTRATION)
            )
            ->setFuelType($fuelType)
            ->setDvlaVehicleId($dvlaVehicle->getDvlaVehicleId());

        if (is_null($make)) {
            $vehicle->setFreeTextMakeName($makeName);
        }

        if (is_null($model)) {
            $vehicle->setFreeTextModelName($modelName);
        }

        $this->importWeight($dvlaVehicle, $vehicle);

        if ($dvlaVehicle->getSecondaryColour()) {
            $vehicle->setSecondaryColour(
                $this->vehicleCatalog->getColourByCode($dvlaVehicle->getSecondaryColour())
            );
        }

        $this->vehicleRepository->save($vehicle);
        $dvlaVehicle->setVehicle($vehicle);
        $this->dvlaVehicleRepository->save($dvlaVehicle);

        $v5cDocumentNumber = $dvlaVehicle->getV5DocumentNumber();
        if (null !== $v5cDocumentNumber) {
            // VM-7220: Create corresponding V5C record.
            $vehicleV5C = (new VehicleV5C())
                ->setVehicle($vehicle)
                ->setV5cRef($dvlaVehicle->getV5DocumentNumber())
                ->setFirstSeen(new \DateTime());
            $this->vehicleV5CRepository->save($vehicleV5C);
        }

        return $vehicle;
    }

    /**
     * @param Person $person
     * @param Vehicle $vehicle
     * @param int $vehicleClassCode
     * @param int $primaryColourCode
     * @param int $secondaryColourCode
     * @param string $fuelTypeCode
     */
    public function logDvlaVehicleImportChanges(
        Person $person,
        Vehicle $vehicle,
        $vehicleClassCode,
        $primaryColourCode,
        $secondaryColourCode,
        $fuelTypeCode
    ) {
        $vehicleClass = $this->vehicleCatalog->getVehicleClassByCode($vehicleClassCode);

        $importChanges = (new DvlaVehicleImportChangeLog())
            ->setTester($person)
            ->setVehicle($vehicle)
            ->setVehicleClass($vehicleClass)
            ->setColour($primaryColourCode)
            ->setSecondaryColour($secondaryColourCode)
            ->setFuelType($fuelTypeCode)
            ->setImported(new \DateTime());

        $this->dvlaVehicleImportChangesRepository->save($importChanges);
    }

    /**
     * @param DvlaVehicle $dvlaVehicle
     * @param Vehicle $vehicle
     */
    private function importWeight(DvlaVehicle $dvlaVehicle, Vehicle $vehicle)
    {

        if (empty($vehicle->getVehicleClass()) || empty($vehicle->getVehicleClass()->getCode()))
        {
             // logic can only be applied with a vehicle class.
             return;
        }

        if ($vehicle->getVehicleClass()->getCode() === Vehicle::VEHICLE_CLASS_1 ||
            $vehicle->getVehicleClass()->getCode() === Vehicle::VEHICLE_CLASS_2)
        {
            // No weight expected to be carried forward for these vehicle classes. 
            return;
        }

        if ($vehicle->getVehicleClass()->getCode() === Vehicle::VEHICLE_CLASS_3 ||
            $vehicle->getVehicleClass()->getCode() === Vehicle::VEHICLE_CLASS_4)
        {
            $massInServiceWeight = $dvlaVehicle->getMassInServiceWeight();
            if (!empty($massInServiceWeight)) {
                $vehicle->setWeight($massInServiceWeight);
                $vehicle->setWeightSource($this->vehicleCatalog->getWeightSourceByCode(WeightSourceCode::MISW));

                return;
            }
 
            // weight not set for class 3 or 4 vehicles if mass in service weight is empty.
            return;
        }

        if ($vehicle->getVehicleClass()->getCode() === Vehicle::VEHICLE_CLASS_5 ||
            $vehicle->getVehicleClass()->getCode() === Vehicle::VEHICLE_CLASS_7)
        {
            $grossWeight = $dvlaVehicle->getDesignedGrossWeight();
            if (!empty($grossWeight)) {
                $vehicle->setWeight($grossWeight);
                $vehicle->setWeightSource($this->vehicleCatalog->getWeightSourceByCode(WeightSourceCode::DGW));
    
                return;
            }
        }
    }

    /**
     * @param array $data
     *
     * @return Vehicle
     */
    private function mapVehicle($data)
    {
        $vehDic = $this->vehicleCatalog;
        $model = $vehDic->findModel($data['make'], $data['model']);
        $make = $vehDic->getMakeByCode($data['make']);

        if (!ctype_digit($data['cylinderCapacity'])) {
            $data['cylinderCapacity'] = null;
        }

        $vehicle = (new Vehicle())
            ->setVin($data['vin'])
            ->setRegistration($data['registrationNumber'])
            ->setCylinderCapacity($data['cylinderCapacity'])
            ->setColour($vehDic->getColourByCode($data['colour']))
            // next two fields MUST be set as they are not captured, this has
            // been done after P.O. (Simon Smith) consultation / discussion.
            ->setFirstUsedDate(DateUtils::toDate($data['dateOfFirstUse']))
            ->setManufactureDate(DateUtils::toDate($data['dateOfFirstUse']))
            ->setFirstRegistrationDate(DateUtils::toDate($data['dateOfFirstUse']))
            ->setFuelType($vehDic->getFuelTypeByCode($data['fuelType']))
            ->setCountryOfRegistration($vehDic->getCountryOfRegistration($data['countryOfRegistration'], true))
            ->setTransmissionType($vehDic->getTransmissionType($data['transmissionType']))
            ->setVehicleClass($vehDic->getVehicleClassByCode($data['testClass']));

        if (is_null($make)) {
            $vehicle->setFreeTextMakeName($data['makeOther']);
            $vehicle->setFreeTextModelName($data['modelOther']);
        } else {
            $vehicle->setMake($make);
            if ($model) {
                $vehicle->setModel($model);
            } else {
                $vehicle->setFreeTextModelName($data['modelOther']);
            }
        }

        if (!empty($data['manufactureDate'])) {
            $vehicle->setManufactureDate(DateUtils::toDate($data['manufactureDate']));
        }
        if (!empty($data['firstRegistrationDate'])) {
            $vehicle->setFirstRegistrationDate(DateUtils::toDate($data['firstRegistrationDate']));
        }

        if (!empty($data['modelType'])) {
            $vehicle->setModelDetail($vehDic->getModelDetail($data['modelType'], true));
        }
        if (!empty($data['bodyType'])) {
            $vehicle->setBodyType($vehDic->findBodyTypeByCode($data['bodyType']));
        }
        if (!empty($data['secondaryColour'])) {
            $vehicle->setSecondaryColour($vehDic->getColourByCode($data['secondaryColour'], true));
        }

        if (!empty($data['emptyVrmReason'])) {
            $vehicle->setEmptyVrmReason($this->vehicleCatalog->getEmptyVrmReasonByCode($data['emptyVrmReason']));
        }
        if (!empty($data['emptyVinReason'])) {
            $vehicle->setEmptyVinReason($this->vehicleCatalog->getEmptyVinReasonByCode($data['emptyVinReason']));
        }

        return $vehicle;
    }
}
