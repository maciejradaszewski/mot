<?php

namespace VehicleApiTest\Controller;

use DvsaCommon\Constants\Role;
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
    protected function setUp()
    {
        parent::setUp();
    }

    public function testGetListCanBeAccessed()
    {

        $searchService = $this->getMockBuilder('DvsaElasticSearch\Service\ElasticSearchService')
            ->disableOriginalConstructor()
            ->setMethods(['findVehicles'])
            ->getMock();

        $searchService
            ->expects($this->once())
            ->method('findVehicles')
            ->will($this->returnValue([]));

        $vehicleSearchParam = $this->getMockBuilder('DvsaEntities\DqlBuilder\SearchParam\VehicleSearchParam')
            ->disableOriginalConstructor()
            ->setMethods(['getSearchType', 'getSearch'])
            ->getMock();

        $controller = new VehicleSearchController($searchService, $vehicleSearchParam);

        $result = $controller->getList();

        $this->assertInstanceOf('Zend\View\Model\JsonModel', $result);


//        $this->mockValidAuthorization([Role::VEHICLE_EXAMINER]);
//        $this->getResultForAction('get');
//        $this->assertResponseStatus(self::HTTP_OK_CODE);
    }

//    public function testGetListThrowAnError()
//    {
//        $this->mockValidAuthorization([Role::VEHICLE_EXAMINER]);
//        $this->getResultForAction('get');
//        $this->assertResponseStatus(self::HTTP_ERR_400);
//    }
}