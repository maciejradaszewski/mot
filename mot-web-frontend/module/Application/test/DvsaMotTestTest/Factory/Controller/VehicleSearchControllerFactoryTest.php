<?php

namespace DvsaMotTestTest\Factory\Controller;

use Application\Service\CatalogService;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\TestUtils\XMock;
use DvsaMotTest\Controller\VehicleSearchController;
use DvsaMotTest\Factory\Controller\VehicleSearchControllerFactory;
use DvsaMotTest\Model\VehicleSearchResult;
use DvsaMotTest\Service\VehicleSearchService;

/**
 * Class VehicleSearchControllerFactoryTest.
 *
 * @covers \DvsaMotTest\Controller\VehicleSearchControllerFactory
 */
class VehicleSearchControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryReturnsVehicleSearchControllerInstance()
    {
        $serviceManager = Bootstrap::getServiceManager();

        $serviceManager->setAllowOverride(true);

        $serviceManager->setService(VehicleSearchService::class, XMock::of(VehicleSearchService::class));
        $serviceManager->setService(ParamObfuscator::class, XMock::of(ParamObfuscator::class));
        $serviceManager->setService('CatalogService', XMock::of(CatalogService::class));
        $serviceManager->setService(VehicleSearchResult::class, XMock::of(VehicleSearchResult::class));

        $controllerManager = $this->getMock('Zend\Mvc\Controller\ControllerManager');
        $controllerManager->expects($this->any())
                          ->method('getServiceLocator')
                          ->will($this->returnValue($serviceManager));

        $factory = new VehicleSearchControllerFactory();

        $this->assertInstanceOf(VehicleSearchController::class, $factory->createService($controllerManager));
    }
}
