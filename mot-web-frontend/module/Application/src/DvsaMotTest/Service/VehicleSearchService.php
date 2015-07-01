<?php

namespace DvsaMotTest\Service;

use Application\Service\CatalogService;
use Application\Service\ContingencySessionManager;
use Core\Service\LazyMotFrontendAuthorisationService;
use DvsaCommon\Dto\Common\MotTestDto;
use DvsaCommon\Dto\Vehicle\VehicleDto;
use DvsaCommon\HttpRestJson\Client;
use DvsaCommon\HttpRestJson\Exception\NotFoundException;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaCommon\UrlBuilder\MotTestUrlBuilder;
use DvsaCommon\UrlBuilder\VehicleUrlBuilder;
use DvsaMotTest\Constants\VehicleSearchSource;
use DvsaMotTest\Model\VehicleSearchResult;
use DvsaMotTest\View\VehicleSearchResult\VehicleSearchResultMessage;
use Zend\Di\Exception\ClassNotFoundException;

/**
 * Class VehicleSearchService
 * @package DvsaMotTest\Service
 */
class VehicleSearchService
{
    const SEARCH_TYPE_CERTIFICATE = 'certificate';
    const SEARCH_TYPE_DEMO = 'demo';
    const SEARCH_TYPE_STANDARD = 'standard';

    const KEY_FOR_PERSON_APPROVED_CLASSES = 'forPerson';
    const KEY_FOR_VTS_APPROVED_CLASSES = 'forVts';

    const PARTIAL_MATCH_VIN_LENGTH = '6';

    const ERROR_MOT_TEST_NOT_FOUND = 'MOT test number not found';

    /** @var Client */
    private $restClient;

    /** @var ParamObfuscator */
    private $paramObfuscator;

    /** @var ContingencySessionManager */
    private $contingencySessionManager;

    /** @var VehicleSearchResult */
    private $vehicleSearchResultModel;

    /** @var CatalogService */
    private $dataCatalogService;

    /** @var LazyMotFrontendAuthorisationService */
    private $authorisationService;

    /**
     * @param Client $restClient
     * @param ParamObfuscator $paramObfuscator
     * @param ContingencySessionManager $contingencySessionManager
     * @param VehicleSearchResult $vehicleSearchResult
     * @param CatalogService $dataCatalogService
     * @param LazyMotFrontendAuthorisationService $authorisationService
     */
    public function __construct(
        Client $restClient,
        ParamObfuscator $paramObfuscator,
        ContingencySessionManager $contingencySessionManager,
        VehicleSearchResult $vehicleSearchResult,
        CatalogService $dataCatalogService,
        LazyMotFrontendAuthorisationService $authorisationService
    ) {
        $this->restClient = $restClient;
        $this->paramObfuscator = $paramObfuscator;
        $this->contingencySessionManager = $contingencySessionManager;
        $this->vehicleSearchResultModel = $vehicleSearchResult;
        $this->dataCatalogService = $dataCatalogService;
        $this->authorisationService = $authorisationService;
    }

    /**
     * @param $vin
     * @param $vrm
     * @param bool $searchDvla
     * @return array
     */
    public function search($vin, $vrm, $searchDvla = false)
    {
        $apiUrl = VehicleUrlBuilder::vehicleList();
        $params = [
            'reg' => $vrm,
            'vin' => $vin,
            'excludeDvla' => !$searchDvla
        ];

        $result = $this->restClient->getWithParams($apiUrl, $params);

        $vehicles = $this->obfuscateIdAndAddSourceToVehicleArray($result['data']['vehicles']);

        for ($i = 0, $vCount = count($vehicles); $i < $vCount; ++$i) {
            $v = $vehicles[$i];
            if (!empty($v['emptyRegistrationReason'])) {
                $reasonCode = $v['emptyRegistrationReason'];
                $v['registration'] = $this->dataCatalogService->getReasonsForEmptyVRM()[$reasonCode];
            }
            if (!empty($v['emptyVinReason'])) {
                $reasonCode = $v['emptyVinReason'];
                $v['vin'] = $this->dataCatalogService->getReasonsForEmptyVin()[$reasonCode];
            }
            $vehicles[$i] = $v;
        }

        $vehicleSearchModel = $this->vehicleSearchResultModel->addResults($vehicles);
        $vehicles = $vehicleSearchModel->getResults();

        return $vehicles;
    }

    /**
     * @param string|null $vrm
     * @param string|null $vin
     * @param int|null $numberOfFoundVehicles
     * @return VehicleSearchResultMessage
     */
    public function getSearchResultMessage($vrm, $vin, $numberOfFoundVehicles)
    {
        $vinLength = strlen($vin);
        $vinGiven = $vinLength > 0;
        $vrmGiven = strlen($vrm) > 0;
        $partialVin = $vinLength == self::PARTIAL_MATCH_VIN_LENGTH;
        $vehiclesString = $numberOfFoundVehicles == 1 ? 'vehicle' : 'vehicles';

        if (!$vrmGiven && !$vinGiven) {
            return new VehicleSearchResultMessage(
                sprintf(
                    "<strong>%s</strong> %s found <strong>without a registration</strong> and <strong>without a VIN</strong>.",
                    $numberOfFoundVehicles,
                    $vehiclesString
                ),
                sprintf("You must enter the registration mark and VIN to search for a vehicle.")
            );
        }

        if ($vrmGiven && $partialVin) {
            return new VehicleSearchResultMessage(
                sprintf(
                    "<strong>%s</strong> %s found with the registration <strong>%s</strong> and a VIN <strong>ending</strong> in <strong>%s</strong>.",
                    $numberOfFoundVehicles,
                    $vehiclesString,
                    $vrm,
                    $vin
                ),
                "Check the vehicle details are correct and try again."
            );
        }

        if ($vrmGiven && $vinGiven && !$partialVin) {
            return new VehicleSearchResultMessage(
                sprintf(
                    "<strong>%s</strong> %s found with the registration <strong>%s</strong> and a VIN <strong>matching %s</strong>.",
                    $numberOfFoundVehicles,
                    $vehiclesString,
                    $vrm,
                    $vin
                ),
                "Only enter the last 6 digits of the VIN if you want to search for a partial match."
            );
        }

        if ($vrmGiven && !$vinGiven) {
            return new VehicleSearchResultMessage(
                sprintf(
                    "<strong>%s</strong> %s found with the registration <strong>%s</strong> and <strong>without a VIN</strong>.",
                    $numberOfFoundVehicles,
                    $vehiclesString,
                    $vrm
                ),
                "You must enter the VIN if the vehicle has one."
            );
        }

        if (!$vrmGiven && $partialVin) {
            return new VehicleSearchResultMessage(
                sprintf(
                    "<strong>%s</strong> %s found <strong>without a registration</strong> and a VIN <strong>ending</strong> in <strong>%s</strong>.",
                    $numberOfFoundVehicles,
                    $vehiclesString,
                    $vin
                ),
                "You must enter the registration mark if the vehicle has one."
            );
        }

        if (!$vrmGiven && $vinGiven && !$partialVin) {
            return new VehicleSearchResultMessage(
                sprintf(
                    "<strong>%s</strong> %s found <strong>without a registration</strong> and a VIN <strong>matching %s</strong>.",
                    $numberOfFoundVehicles,
                    $vehiclesString,
                    $vin
                ),
                "You must enter the registration mark if the vehicle has one. Only enter the last 6 digits of the VIN if you want to search for a partial match."
            );
        }

        return new VehicleSearchResultMessage(
            sprintf(
                "<strong>%s</strong> %s found <strong>without a registration</strong> and <strong>without a VIN.</strong>",
                $numberOfFoundVehicles,
                $vehiclesString
            ),
            ""
        );
    }

    /**
     * Get Vehicle Data by MotTest Number.
     * If there are any REST errors, we want to know.  Only catching NotFoundException
     *
     * @param string $motTestNumber
     *
     * @return VehicleDto|false
     */
    public function getVehicleFromMotTestCertificate($motTestNumber)
    {
        if (is_null($motTestNumber)) {
            return false;
        }

        $apiUrl = MotTestUrlBuilder::motTest($motTestNumber);

        try {
            $result = $this->restClient->get($apiUrl->toString());
            /** @var MotTestDto $motDetails */
            $motDetails = $result['data'];

            return $motDetails->getVehicle();
        } catch (NotFoundException $e) {
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get Vehicle Data by MotTest Number When Trying a Re-test
     *
     * @param string $motTestNumber
     *
     * @return VehicleDto
     */
    public function getVehicleFromMotTestCertificateForRetest($motTestNumber)
    {
        $apiUrl = MotTestUrlBuilder::motValidateRetest($motTestNumber);

        $result = $this->restClient->get($apiUrl->toString());
        /** @var MotTestDto $motDetails */
        $motDetails = $result['data'];

        return $motDetails->getVehicle();
    }

    /**
     * @param $searchType
     * @return bool
     */
    public function shouldSearchInDvlaVehicleList($searchType)
    {
        return ($searchType === self::SEARCH_TYPE_STANDARD);
    }

    /**
     * @param $searchType
     * @return bool
     */
    public function areSlotsNeeded($searchType)
    {
        // If the user is not a tester then slots are not needed.
        if (!$this->authorisationService->isTester()) {
            return false;
        }

        if ($searchType === self::SEARCH_TYPE_CERTIFICATE || $searchType === self::SEARCH_TYPE_DEMO) {
            return false;
        }

        return true;
    }

    /**
     * @param array $vehicleArray
     * @return array
     */
    public function obfuscateIdAndAddSourceToVehicleArray(array $vehicleArray)
    {
        array_walk(
            $vehicleArray,
            function (&$item) {
                $item['source'] = (
                $item['isDvla'] === true
                    ? VehicleSearchSource::DVLA
                    : VehicleSearchSource::VTR
                );

                $item['id'] = $this->paramObfuscator->obfuscateEntry(
                    ParamObfuscator::ENTRY_VEHICLE_ID,
                    $item['id']
                );
            }
        );

        return $vehicleArray;
    }

}
