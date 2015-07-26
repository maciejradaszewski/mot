<?php

namespace VehicleApiTest\Controller;

use DvsaCommon\Constants\Role;
use DvsaCommonTest\TestUtils\XMock;
use DvsaElasticSearch\Service\ElasticSearchService;
use DvsaMotApiTest\Controller\AbstractMotApiControllerTestCase;
use VehicleApi\Controller\VehicleSearchController;
use VehicleApi\Service\VehicleSearchService;
use DvsaEntities\DqlBuilder\SearchParam\VehicleSearchParam;

/**
 * Class VehicleSearchControllerTest
 *
 * @package DvsaMotApiTest\Controller
 */
class VehicleSearchControllerTest extends \PHPUnit_Framework_TestCase
{

    public function testGivenInputValidationPassesSearchReturnsResults()
    {
        $searchService = $this->getMockBuilder(VehicleSearchService::class)
            ->disableOriginalConstructor()
            ->setMethods(['searchVehicleWithAdditionalData'])
            ->getMock();

        $vehicleSearchParam = new VehicleSearchParam('test', 'vin');

        $controller = new VehicleSearchController($searchService, $vehicleSearchParam);

        $result = $controller->getList();

        $this->assertInstanceOf('Zend\View\Model\JsonModel', $result);
    }

}