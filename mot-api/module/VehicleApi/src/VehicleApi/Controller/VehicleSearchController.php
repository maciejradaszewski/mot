<?php

namespace VehicleApi\Controller;

use DvsaCommonApi\Model\ApiResponse;
use DvsaCommonApi\Controller\AbstractDvsaRestfulController;
use DvsaEntities\DqlBuilder\SearchParam\VehicleSearchParam;
use DvsaMotApi\Model\OutputFormat;
use Zend\I18n\Validator\DateTime;
use DvsaElasticSearch\Service\ElasticSearchService as SearchService;

/**
 * Class VehicleSearchController
 *
 * @package DvsaMotApi\Controller
 */
class VehicleSearchController extends AbstractDvsaRestfulController
{
    const SEARCH_PARAM_REQUIRED_DISPLAY_MESSAGE =
        'Missing Vehicle VRM/VIN number. Please enter a VRM/VIN to search for.';

    /**
     * @var SearchService
     */
    protected $searchService;

    /**
     * @var VehicleSearchParam
     */
    protected $vehicleSearchParam;

    /**
     * @param SearchService $searchService
     * @param VehicleSearchParam $vehicleSearchParam
     */
    public function __construct(SearchService $searchService, VehicleSearchParam $vehicleSearchParam)
    {
        $this->searchService = $searchService;
        $this->vehicleSearchParam = $vehicleSearchParam;
    }

    /**
     * Search for Vehicles by Registration or VIN.
     */
    public function getList()
    {
        try {
            $vehicles = $this->searchService->findVehicles($this->vehicleSearchParam);
            return ApiResponse::jsonOk($vehicles);

        } catch (\UnexpectedValueException $e) {
            return $this->returnBadRequestResponseModel(
                $e->getMessage(),
                self::ERROR_CODE_REQUIRED,
                self::SEARCH_PARAM_REQUIRED_DISPLAY_MESSAGE
            );
        }
    }
}
