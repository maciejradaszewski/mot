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
        $this->authService->assertGranted(PermissionInSystem::VEHICLE_READ);

        $vehicles = $this->vehicleRepository->search($vin, $reg, $isFullVin, $limit);
        if (!empty($vehicles)) {
            return $this->extractVehicles($vehicles);
        }

        if ($searchDvla) {
            $vehicles = $this->dvlaVehicleRepository->search($vin, $reg, $isFullVin, $limit);
            if (!empty($vehicles)) {
                return $this->extractDvlaVehicles($vehicles);
            }
        }

        if ($this->allowFuzzySearch) {
            $simpleCharMapping = FuzzySearchRegexHelper::charGroupsToMapping(
                FuzzySearchRegexHelper::uppercaseCharGroups(self::$simpleCharGroups)
            );

            $vehicles = $this->vehicleRepository->fuzzySearch($vin, $reg, $simpleCharMapping, $limit);

            if (!empty($vehicles)) {
                return $this->extractVehicles($vehicles);
            }

            if ($searchDvla) {
                $vehicles = $this->dvlaVehicleRepository->fuzzySearch($vin, $reg, $simpleCharMapping, $limit);
                if (!empty($vehicles)) {
                    return $this->extractDvlaVehicles($vehicles);
                }
            }
        }

        return [];
    }

    public function searchVehicleWithMotData($vin = null, $reg = null, $isFullVin = null, $searchDvla = null, $limit = null)
    {
        $this->authService->assertGranted(PermissionInSystem::VEHICLE_READ);

        $vehicles = $this->vehicleRepository->searchVehicle($vin, $reg, $isFullVin, $limit);

        if (!empty($vehicles)) {
            return $this->extractVehiclesWithMotData($vehicles);
        }

        if ($searchDvla) {
            $vehicles = $this->dvlaVehicleRepository->searchVehicle($vin, $reg, $isFullVin, $limit);
            if (!empty($vehicles)) {
                return $this->extractDvlaVehicles($vehicles);
            }
        }

        return [];
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

    private function extractVehiclesWithMotData($vehicles)
    {
        $vehicleData = array_map(
            function ($vehicle) {
                if ($vehicle instanceof Vehicle) {
                    return $this->extractVehicle($vehicle, true);
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
    public function extractVehicle(Vehicle $v, $getMotTestData = false)
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

        if ($getMotTestData) {
            $motTestData = $this->motTestRepository->findHistoricalTestsForVehicle($v->getId(), null);

            $result = array_merge(
                $result,
                [
                    'mot_id'              => '',
                    'mot_completion_date' => '',
                    'total_mot_tests'     => '0',
                ]
            );

            if ($motTestData) {
                /** @var MotTest $latestMotTest */
                $latestMotTest = current($motTestData);
                $result['mot_id'] = $latestMotTest->getNumber();
                $result['mot_completed_date'] = $latestMotTest->getIssuedDate()->format('Y-m-d');
                $result['total_mot_tests'] = count($motTestData);
            }
        }

        return $result;
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
}
