<?php

namespace VehicleApi\Service;

use DataCatalogApi\Service\VehicleCatalogService;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaCommon\Utility\ArrayUtils;
use DvsaEntities\Entity\Colour;
use DvsaEntities\Entity\DvlaVehicle;
use DvsaEntities\Entity\FuelType;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Entity\Vehicle;
use DvsaEntities\Repository\DvlaVehicleImportChangesRepository;
use DvsaEntities\Repository\DvlaVehicleRepository;
use DvsaEntities\Repository\MotTestRepository;
use DvsaEntities\Repository\VehicleRepository;
use DvsaMotApi\Helper\FuzzySearchRegexHelper;
use DvsaMotApi\Service\TesterService;

/**
 * Class VehicleService.
 */
class VehicleSearchService
{
    private static $simpleCharGroups
        = [
            ['0', 'O'],
            ['1', 'I'],
        ];

    private static $fullCharGroups
        = [
            ['0', 'O'],
            ['1', 'I', 'l'],
            ['3', 'E'],
            ['4', 'h', 'A'],
            ['5', 'S'],
            ['6', 'b', 'G'],
            ['7', 'L', 'T'],
            ['8', 'B'],
            ['9', 'g', 'q'],
        ];

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
    /** @var bool */
    private $allowFuzzySearch;

    /**
     * @param AuthorisationServiceInterface      $authService
     * @param VehicleRepository                  $vehicleRepository
     * @param DvlaVehicleRepository              $dvlaVehicleRepository
     * @param DvlaVehicleImportChangesRepository $dvlaVehicleImportChangesRepository
     * @param MotTestRepository                  $motTestRepository
     * @param TesterService                      $testerService
     * @param VehicleCatalogService              $vehicleCatalog
     * @param ParamObfuscator                    $paramObfuscator
     * @param $vehicleSearchFuzzyEnabled
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
        $vehicleSearchFuzzyEnabled
    ) {
        $this->vehicleRepository = $vehicleRepository;
        $this->dvlaVehicleRepository = $dvlaVehicleRepository;
        $this->authService = $authService;
        $this->vehicleCatalog = $vehicleCatalog;
        $this->dvlaVehicleImportChangesRepository = $dvlaVehicleImportChangesRepository;
        $this->testerService = $testerService;
        $this->paramObfuscator = $paramObfuscator;
        $this->allowFuzzySearch = $vehicleSearchFuzzyEnabled;
        $this->motTestRepository = $motTestRepository;
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

        if(empty($vehicles)) {
            if($this->paramsNeedStripping($vin, $reg)) {
                list($vin, $reg) = $this->stripParams($vin, $reg);
                $vehicles = $this->searchAndExtractVehicle($vin, $reg, $isFullVin, $searchDvla, $limit);
            }
        } else {
            $exactMatch = true;
        }

        if(empty($vehicles)) {
            $vehicles = [];
        }


        return [$vehicles, $exactMatch];
    }

    public function searchVehicleWithMotData($vin = null, $reg = null, $isFullVin = null, $searchDvla = null, $limit = null)
    {
        $this->authService->assertGranted(PermissionInSystem::VEHICLE_READ);

        $vehicles = $this->searchAndExtractVehicle($vin, $reg, $isFullVin, $searchDvla, $limit);

        if(empty($vehicles) && $this->paramsNeedStripping($vin, $reg)) {
            list($vin, $reg) = $this->stripParams($vin, $reg);
            $vehicles = $this->searchAndExtractVehicle($vin, $reg, $isFullVin, $searchDvla, $limit);
        }

        if(!empty($vehicles)) {
            $vehicles = $this->mergeMotDataToVehicles($vehicles);
        }

        return !empty($vehicles) ? $vehicles : [];
    }

    private function searchAndExtractVehicle($vin = null, $reg = null, $isFullVin = null, $searchDvla = null, $limit = null)
    {
        $vehicles = $this->vehicleRepository->searchVehicle($vin, $reg, $isFullVin, $limit);

        if (!empty($vehicles)) {
            return $this->extractVehicles($vehicles);
        }

        if ($searchDvla) {
            $vehicles = $this->dvlaVehicleRepository->search($vin, $reg, $isFullVin, $limit);
            if (!empty($vehicles)) {
                return $this->extractDvlaVehicles($vehicles);
            }
        }
    }

    /**
     * @param string  $vin        VIN number
     * @param string  $reg        Registration number
     * @param bool    $searchDvla True to search DVLA data source as well
     * @param integer $limit      Max records to search for in the query
     *
     * @return array
     */
    public function fuzzySearch($vin, $reg, $searchDvla, $limit)
    {
        $fullCharMapping = FuzzySearchRegexHelper::charGroupsToMapping(
            FuzzySearchRegexHelper::uppercaseCharGroups(self::$fullCharGroups)
        );
        $vehicles = $this->vehicleRepository->fuzzySearch($vin, $reg, $fullCharMapping, $limit);
        if (!empty($vehicles)) {
            return $this->extractVehicles($vehicles);
        }

        if ($searchDvla) {
            $vehicles = $this->dvlaVehicleRepository->fuzzySearch($vin, $reg, $fullCharMapping, $limit);
            if (!empty($vehicles)) {
                return $this->extractDvlaVehicles($vehicles);
            }
        }

        return [];
    }

    private function mergeMotDataToVehicles($vehicles)
    {
        $vehicleData = array_map(
            function ($vehicle) {
                if (is_array($vehicle)) {
                    return $this->mergeMotDataToVehicle($vehicle);
                }
            },
            $vehicles
        );

        $vehicleData = ArrayUtils::sortByDesc($vehicleData, 'mot_completion_date');

        return $vehicleData;
    }

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
    public function extractVehicle(Vehicle $v)
    {
        $emptyVrmReason = $v->getEmptyVrmReason() ? $v->getEmptyVrmReason()->getCode() : null;
        $emptyVinReason = $v->getEmptyVinReason() ? $v->getEmptyVinReason()->getCode() : null;

        $result = [
            'id'                      => $v->getId(),
            'registration'            => $v->getRegistration(),
            'emptyRegistrationReason' => $emptyVrmReason,
            'vin'                     => $v->getVin(),
            'emptyVinReason'          => $emptyVinReason,
            'year'                    => $v->getYear(),
            'firstUsedDate'           => DateTimeApiFormat::date($v->getFirstUsedDate()),
            'cylinderCapacity'        => $v->getCylinderCapacity(),
            'make'                    => $v->getMakeName(),
            'model'                   => $v->getModelName(),
            'modelDetail'             => $v->getModelDetail() ? $v->getModelDetail()->getName() : null,
            'vehicleClass'            => $v->getVehicleClass() ? $v->getVehicleClass()->getCode() : null,
            'primaryColour'           => self::extractColour($v->getColour()),
            'secondaryColour'         => self::extractColour($v->getSecondaryColour()),
            'fuelType'                => self::extractFuelType($v->getFuelType()),
            'bodyType'                => $v->getBodyType() ? $v->getBodyType()->getName() : null,
            'transmissionType'        => $v->getTransmissionType() ? $v->getTransmissionType()->getName() : null,
            'weight'                  => $v->getWeight(),
            'isDvla'                  => false,
            'creationDate'            => DateTimeApiFormat::date($v->getCreatedOn()),
        ];

        return $result;
    }

    /**
     * @param array $vehicle
     * @return array
     */
    private function mergeMotDataToVehicle(array $vehicle)
    {
        $motTestData = $this->motTestRepository->findHistoricalTestsForVehicle($vehicle['id'], null);

        $vehicle = array_merge(
            $vehicle,
            [
                'mot_id'              => '',
                'mot_completion_date' => '',
                'total_mot_tests'     => '0',
            ]
        );

        if ($motTestData) {
            /** @var MotTest $latestMotTest */
            $latestMotTest = current($motTestData);
            $vehicle['mot_id'] = $latestMotTest->getNumber();
            $vehicle['mot_completed_date'] = $latestMotTest->getIssuedDate()->format('Y-m-d');
            $vehicle['total_mot_tests'] = count($motTestData);
        }

        return $vehicle;
    }

    /**
     * @param DvlaVehicle $v
     *
     * @return array
     */
    public function extractDvlaVehicle(DvlaVehicle $v)
    {
        $fuelTypeEntity = $this->findFuelTypeByPropulsionCode($v->getFuelType());
        // Search in DVSA make and model tables if no mapping is found
        $fallbackToDvsa = true;
        $map = $this->vehicleCatalog->getMakeModelMapByDvlaCode(
            $v->getMakeCode(), $v->getModelCode(), $fallbackToDvsa
        );

        if ($map) {
            $makeName        = $map->getMake() ? $map->getMake()->getName() : $v->getMakeInFull();
            $modelName       = $map->getModel() ? $map->getModel()->getName() : '';
            $modelDetailName = $map->getModelDetail() ? $map->getModelDetail()->getName() : '';
        } else {
            $makeName        = $this->vehicleCatalog->getMakeNameByDvlaCode($v->getMakeCode());
            $modelName       = $this->vehicleCatalog->getModelNameByDvlaCode($v->getMakeCode(), $v->getModelCode());
            $modelDetailName = '';

            if (!$makeName && !$modelName && $v->getMakeInFull()) {
                $makeName = $v->getMakeInFull();
            }
        }

        $result = [
            'id'                => $v->getId(),
            'registration'      => $v->getRegistration(),
            'vin'               => $v->getVin(),
            'cylinderCapacity'  => $v->getCylinderCapacity(),
            'make'              => $makeName,
            'model'             => $modelName,
            'modelDetail'       => $modelDetailName,
            'primaryColour'     => $this->extractColourForCode($v->getPrimaryColour()),
            'secondaryColour'   => $this->extractOptionalColourForCode($v->getSecondaryColour()),
            'fuelType'          => self::extractFuelType($fuelTypeEntity),
            'bodyType'          => $this->extractBodyTypeName($v->getBodyType()),
            'firstUsedDate'     => DateTimeApiFormat::date($v->getFirstUsedDate()),
            'transmissionType'  => '', // FIXME: Implemente migration
            'weight'            => $v->getDesignedGrossWeight(),
            'isDvla'            => true,
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
            'id'   => $c->getId(),
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
            'id'   => $f->getId(),
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
     * @param string $vin
     * @param string $reg
     * @return bool
     */
    private function paramsNeedStripping($vin, $reg)
    {
        if(strpos($vin, " ") !== FALSE) {
            return true;
        }

        if(strpos($reg, " ") !== FALSE) {
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
        if(strpos($vin, " ") !== FALSE) {
            $vin = preg_replace('/\s+/', '', $vin);
        }

        if(strpos($reg, " ") !== FALSE) {
            $reg = preg_replace('/\s+/', '', $reg);
        }

        return array($vin, $reg);
    }
}
