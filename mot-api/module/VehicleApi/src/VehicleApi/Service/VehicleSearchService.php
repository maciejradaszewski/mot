<?php

namespace VehicleApi\Service;

use DataCatalogApi\Service\VehicleCatalogService;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaCommon\Utility\ArrayUtils;
use DvsaEntities\DataConversion\AbstractStringConverter;
use DvsaEntities\DqlBuilder\SearchParam\VehicleSearchParam;
use DvsaEntities\Entity\Colour;
use DvsaEntities\Entity\DvlaVehicle;
use DvsaEntities\Entity\FuelType;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Entity\Vehicle;
use DvsaEntities\Repository\DvlaVehicleImportChangesRepository;
use DvsaEntities\Repository\DvlaVehicleRepository;
use DvsaEntities\Repository\MotTestRepository;
use DvsaEntities\Repository\VehicleRepository;
use DvsaMotApi\Service\TesterService;
use DvsaMotApi\Service\Validator\RetestEligibility\RetestEligibilityValidator;
use DvsaCommonApi\Service\Exception\BadRequestException;

/**
 * Class VehicleService.
 */
class VehicleSearchService
{

    /** @var  VehicleRepository */
    protected $vehicleRepository;
    /** @var  DvlaVehicleRepository */
    protected $dvlaVehicleRepository;
    /** @var  MotTestRepository */
    protected $motTestRepository;
    /** @var  AuthorisationServiceInterface */
    protected $authService;
    /** @var \DvsaEntities\Repository\DvlaVehicleImportChangesRepository */
    private $dvlaVehicleImportChangesRepository;
    /** @var TesterService */
    private $testerService;
    /** @var VehicleCatalogService */
    private $vehicleCatalog;
    /** @var  ParamObfuscator */
    private $paramObfuscator;
    /** @var RetestEligibilityValidator */
    private $retestEligibilityValidator;
    /** @var AbstractStringConverter */
    private $searchStringConverter;
    /** @var  AbstractStringConverter */
    private $enforcementSearchStringConverter;

    /**
     * @param AuthorisationServiceInterface $authService
     * @param VehicleRepository $vehicleRepository
     * @param DvlaVehicleRepository $dvlaVehicleRepository
     * @param DvlaVehicleImportChangesRepository $dvlaVehicleImportChangesRepository
     * @param MotTestRepository $motTestRepository
     * @param TesterService $testerService
     * @param VehicleCatalogService $vehicleCatalog
     * @param ParamObfuscator $paramObfuscator
     * @param RetestEligibilityValidator $retestEligibilityValidator
     * @param AbstractStringConverter $searchStringConverter
     * @param AbstractStringConverter $enforcementSearchStringConverter
     */
    public function __construct(
        AuthorisationServiceInterface $authService,
        VehicleRepository $vehicleRepository,
        DvlaVehicleRepository $dvlaVehicleRepository,
        DvlaVehicleImportChangesRepository $dvlaVehicleImportChangesRepository,
        MotTestRepository $motTestRepository,
        TesterService $testerService,
        VehicleCatalogService $vehicleCatalog,
        ParamObfuscator $paramObfuscator,
        RetestEligibilityValidator $retestEligibilityValidator,
        AbstractStringConverter $searchStringConverter,
        AbstractStringConverter $enforcementSearchStringConverter
    ) {
        $this->vehicleRepository = $vehicleRepository;
        $this->dvlaVehicleRepository = $dvlaVehicleRepository;
        $this->authService = $authService;
        $this->vehicleCatalog = $vehicleCatalog;
        $this->dvlaVehicleImportChangesRepository = $dvlaVehicleImportChangesRepository;
        $this->testerService = $testerService;
        $this->paramObfuscator = $paramObfuscator;
        $this->motTestRepository = $motTestRepository;
        $this->retestEligibilityValidator = $retestEligibilityValidator;
        $this->searchStringConverter = $searchStringConverter;
        $this->enforcementSearchStringConverter = $enforcementSearchStringConverter;
    }

    /**
     * @param string  $vin        VIN number
     * @param string  $reg        Registration number
     * @param bool    $isFullVin  Indicates whether passed VIN number is full
     * @param bool    $searchDvla True to search DVLA data source as well
     * @param integer $limit      Max records to search for in the query
     *
     * @return array
     */
    public function search($vin = null, $reg = null, $isFullVin = null, $searchDvla = null, $limit = null)
    {
        $exactMatch = false;
        $this->authService->assertGranted(PermissionInSystem::VEHICLE_READ);

        $vehicles = $this->searchAndExtractVehicle($vin, $reg, $isFullVin, $searchDvla, $limit);

        if (empty($vehicles)) {
                $vin = $this->searchStringConverter->convert($vin);
                $reg = $this->searchStringConverter->convert($reg);
                $vehicles = $this->searchAndExtractVehicle($vin, $reg, $isFullVin, $searchDvla, $limit);
        } else {
            $exactMatch = true;
        }

        if (empty($vehicles)) {
            $vehicles = [];
        }

        return [$vehicles, $exactMatch];
    }

    /**
     * @param VehicleSearchParam $searchParam
     * @return mixed
     */
    public function searchVehicleWithAdditionalData(VehicleSearchParam $searchParam)
    {
        $searchParam->process();

        $vin = $this->enforcementSearchStringConverter->convert($searchParam->getVin());
        $reg = $this->enforcementSearchStringConverter->convert($searchParam->getRegistration());

        $vehicles = $this->vehicleRepository->search(
            $vin,
            $reg
        );

        if ($vehicles) {
            $vehicles = $this->extractEnforcementVehicles($vehicles);
        } else {
            $vehicles = [];
        }

        $search['resultCount'] = count($vehicles);
        $search['totalResultCount'] = count($vehicles);
        $search['data'] = $vehicles;
        $search['searched'] = ['isElasticSearch' => false] + $searchParam->toArray();

        return $search;
    }

    /**
     * @param Vehicle[] $vehicles
     * @return array
     */
    private function extractEnforcementVehicles($vehicles)
    {
        $results = [];
        foreach ($vehicles as $vehicle) {
            $results[$vehicle->getId()] = [
                'id'            => $vehicle->getId(),
                'vin'           => $vehicle->getVin(),
                'registration'  => $vehicle->getRegistration(),
                'make'          => $vehicle->getMakeName(),
                'model'         => $vehicle->getModelName(),
                'displayDate'   => $vehicle->getLastUpdatedOn() !== null ?
                    $vehicle->getLastUpdatedOn()->format('d M Y') :
                    null,
            ];
        }
        return $results;
    }

    public function searchVehicleWithMotData(
        $vin = null,
        $reg = null,
        $searchDvla = null,
        $limit = null,
        $vtsId = false,
        $contingencyDto = null
    ) {
        $vin = $this->searchStringConverter->convert($vin);
        $reg = $this->searchStringConverter->convert($reg);

        $isFullVin = $this->isVinFull($vin);
        $this->authService->assertGranted(PermissionInSystem::VEHICLE_READ);

        $vehicles = $this->searchAndExtractVehicle($vin, $reg, $isFullVin, $searchDvla, $limit);

        if (!empty($vehicles)) {
            $vehicles = $this->mergeMotDataToVehicles($vehicles, $vtsId, $contingencyDto);
        }

        return !empty($vehicles) ? $vehicles : [];
    }

    private function searchAndExtractVehicle(
        $vin = null,
        $reg = null,
        $isFullVin = null,
        $searchDvla = null,
        $limit = null
    ) {
        $vehicles = $this->vehicleRepository->searchVehicle($vin, $reg, $isFullVin, $limit);

        if (!empty($vehicles)) {
            return $this->extractVehicles($vehicles);
        }

        if ($searchDvla) {
            $vehicles = $this->dvlaVehicleRepository->searchVehicle($vin, $reg, $isFullVin, 10);
            if (!empty($vehicles)) {
                return $this->extractDvlaVehicles($vehicles);
            }
        }
    }

    /**
     * @param array $vehicles
     * @param bool $vtsId
     * @return array
     */
    private function mergeMotDataToVehicles($vehicles, $vtsId = false, $contingencyDto = null)
    {
        foreach ($vehicles as &$vehicle) {
            if (is_array($vehicle)) {
                $vehicle = $this->mergeMotDataToVehicle($vehicle, $vtsId, $contingencyDto);
            }
        }

        $vehicleData = ArrayUtils::sortByDesc($vehicles, 'mot_completed_date');

        return $vehicleData;
    }

    /**
     * @param array $vehicles
     * @return array
     */
    private function extractVehicles($vehicles)
    {
        return array_map(
            function ($vehicle) {
                if ($vehicle instanceof Vehicle) {
                    return $this->extractVehicle($vehicle);
                }
            },
            $vehicles
        );
    }

    protected function extractDvlaVehicles($vehicles)
    {
        return array_map(
            function ($vehicle) {
                if ($vehicle instanceof DvlaVehicle) {
                    return $this->extractDvlaVehicle($vehicle);
                }
            },
            $vehicles
        );
    }

    /**
     * @param Vehicle $v
     *
     * 'mot_id' => $vehicle['mot_id'],
     *
     * @return array
     */
    private function extractVehicle(Vehicle $v)
    {
        $emptyVrmReason = $v->getEmptyVrmReason() ? $v->getEmptyVrmReason()->getCode() : null;
        $emptyVinReason = $v->getEmptyVinReason() ? $v->getEmptyVinReason()->getCode() : null;

        $result = [
            'id' => $v->getId(),
            'registration' => $v->getRegistration(),
            'emptyRegistrationReason' => $emptyVrmReason,
            'vin' => $v->getVin(),
            'emptyVinReason' => $emptyVinReason,
            'year' => $v->getYear(),
            'firstUsedDate' => DateTimeApiFormat::date($v->getFirstUsedDate()),
            'cylinderCapacity' => $v->getCylinderCapacity(),
            'make' => $v->getMakeName(),
            'model' => $v->getModelName(),
            'modelDetail' => $v->getModelDetail() ? $v->getModelDetail()->getName() : null,
            'vehicleClass' => $v->getVehicleClass() ? $v->getVehicleClass()->getCode() : null,
            'primaryColour' => self::extractColour($v->getColour()),
            'secondaryColour' => self::extractColour($v->getSecondaryColour()),
            'fuelType' => self::extractFuelType($v->getFuelType()),
            'bodyType' => $v->getBodyType() ? $v->getBodyType()->getName() : null,
            'transmissionType' => $v->getTransmissionType() ? $v->getTransmissionType()->getName() : null,
            'weight' => $v->getWeight(),
            'isDvla' => false,
            'creationDate' => DateTimeApiFormat::date($v->getCreatedOn()),
        ];

        return $result;
    }

    /**
     * @param array $vehicle
     * @return array
     */
    private function mergeMotDataToVehicle(array $vehicle, $vtsId = false, $contingencyDto = null)
    {
        $vehicle = array_merge(
            $vehicle,
            [
                'mot_id' => '',
                'mot_completed_date' => '',
                'total_mot_tests' => '0',
            ]
        );

        if (!$vehicle['isDvla']) {
            $motTestData = $this->motTestRepository->findHistoricalTestsForVehicle($vehicle['id'], null);

            if ($motTestData) {
                /** @var MotTest $latestMotTest */
                $latestMotTest = current($motTestData);
                $vehicle['mot_id'] = $latestMotTest->getNumber();
                $completedDate = $latestMotTest->getIssuedDate() ? $latestMotTest->getIssuedDate() : $latestMotTest->getStartedDate();
                $vehicle['mot_completed_date'] = $completedDate->format('Y-m-d');
                $vehicle['total_mot_tests'] = count($motTestData);
            }
        }

        if ($vtsId) {
            try {
                $this->retestEligibilityValidator
                     ->checkEligibilityForRetest(
                         $vehicle['id'],
                         $vtsId,
                         $contingencyDto
                     );

                $vehicle['retest_eligibility'] = true;
            } catch(BadRequestException $exception) {
                $vehicle['retest_eligibility'] = false;
            }
        }

        return $vehicle;
    }

    /**
     * @param DvlaVehicle $v
     *
     * @return array
     */
    private function extractDvlaVehicle(DvlaVehicle $v)
    {
        $fuelTypeEntity = $this->findFuelTypeByPropulsionCode($v->getFuelType());
        $modelDetailName = '';

        if ($v->getMakeInFull()) {
            $makeName = $v->getMakeInFull();
        }
        else
        {
            $map = $this->vehicleCatalog->getMakeModelMapByDvlaCode($v->getMakeCode(), $v->getModelCode());

            if ($map) {
                $makeName = $map->getMake() ? $map->getMake()->getName() : $this->vehicleCatalog->getMakeNameByDvlaCode($v->getMakeCode());
                $modelName = $map->getModel() ? $map->getModel()->getName() : $this->vehicleCatalog->getModelNameByDvlaCode($v->getMakeCode(), $v->getModelCode());
                $modelDetailName = $map->getModelDetail() ? $map->getModelDetail()->getName() : '';
            } else {
                $makeName = $this->vehicleCatalog->getMakeNameByDvlaCode($v->getMakeCode());
                $modelName = $this->vehicleCatalog->getModelNameByDvlaCode($v->getMakeCode(), $v->getModelCode());
            }
        }

        $result = [
            'id' => $v->getId(),
            'registration' => $v->getRegistration(),
            'vin' => $v->getVin(),
            'cylinderCapacity' => $v->getCylinderCapacity(),
            'make' => $makeName,
            'model' => $modelName,
            'modelDetail' => $modelDetailName,
            'primaryColour' => $this->extractColourForCode($v->getPrimaryColour()),
            'secondaryColour' => $this->extractOptionalColourForCode($v->getSecondaryColour()),
            'fuelType' => self::extractFuelType($fuelTypeEntity),
            'bodyType' => $this->extractBodyTypeName($v->getBodyType()),
            'firstUsedDate' => DateTimeApiFormat::date($v->getFirstUsedDate()),
            'transmissionType' => '', // FIXME: Implemente migration
            'weight' => $v->getDesignedGrossWeight(),
            'isDvla' => true,
        ];

        return $result;
    }

    /**
     * @param string $code
     *
     * @return FuelType|null
     */
    private function findFuelTypeByPropulsionCode($code)
    {
        if ($code) {
            return $this->vehicleCatalog->findFuelTypeByPropulsionCode($code);
        }

        return;
    }

    private function extractBodyTypeName($bodyTypeCode)
    {
        $bodyTypeName = null;
        if ($bodyTypeCode) {
            $bodyType = $this->vehicleCatalog->findBodyTypeByCode($bodyTypeCode);
            $bodyTypeName = ($bodyType) ? $bodyType->getName() : null;
        }

        return $bodyTypeName;
    }

    /**
     * Returns array [id => '..', name => '..'] or null.
     *
     * @param Colour $c
     *
     * @return array|null
     */
    private static function extractColour(Colour $c = null)
    {
        return $c ? [
            'id' => $c->getId(),
            'name' => $c->getName(),
        ] : null;
    }

    /**
     * Returns array [id => '..', name => '..'] or null.
     *
     * @param FuelType $f
     *
     * @return array|null
     */
    private static function extractFuelType(FuelType $f = null)
    {
        return $f ? [
            'id' => $f->getId(),
            'name' => $f->getName(),
        ] : null;
    }

    /**
     * Returns array [id => '..', name => '..'] or null.
     *
     * @param string $code
     *
     * @return array|null
     */
    private function extractColourForCode($code)
    {
        $colour = $this->vehicleCatalog->getColourByCode($code);

        return self::extractColour($colour);
    }

    /**
     * @param string $code
     *
     * @return array|null
     */
    private function extractOptionalColourForCode($code)
    {
        $colour = $this->vehicleCatalog->findColourByCode($code);

        return self::extractColour($colour);
    }

    /**
     * Chcecks if VIN is full
     * @param $vin
     * @return bool
     */
    public function isVinFull($vin)
    {
        return  (is_string($vin)) && (!empty($vin)) && (strlen($vin) !== 6);
    }

    /**
     * @param string $vin
     * @param string $reg
     * @return bool
     */
    private function paramsNeedStripping($vin, $reg)
    {
        if (strpos($vin, " ") !== false) {
            return true;
        }

        if (strpos($reg, " ") !== false) {
            return true;
        }
    }

    /**
     * @param string $vin
     * @param string $reg
     * @return array
     */
    private function stripParams($vin, $reg)
    {
        if (strpos($vin, " ") !== false) {
            $vin = preg_replace('/\s+/', '', $vin);
        }

        if (strpos($reg, " ") !== false) {
            $reg = preg_replace('/\s+/', '', $reg);
        }

        return array($vin, $reg);
    }
}
