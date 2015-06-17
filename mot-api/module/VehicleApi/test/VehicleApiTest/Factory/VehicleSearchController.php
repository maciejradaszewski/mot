<?php

namespace VehicleApiTest\Controller;

use DvsaMotApiTest\Controller\AbstractMotApiControllerTestCase;
use VehicleApi\Factory\Controller\VehicleSearchControllerFactory;
use Zend\ServiceManager\ServiceManager;

class ZfcUserAuthenticationFactoryTest extends AbstractMotApiControllerTestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager;

        $serviceManager->setService(VehicleSearchService::class, $this->getMock(VehicleSearchService::class));
        $serviceManager->setService(VehicleSearchParam::class, $this->getMock(VehicleSearchParam::class));

        $plugins = $this->getMock('Zend\ServiceManager\AbstractPluginManager');
        $plugins->expects($this->any())
            ->method('getServiceLocator')
            ->will($this->returnValue($serviceManager));

        $factory = new VehicleSearchControllerFactory;
        $factoryResult = $factory->createService($plugins);
        $this->assertInstanceOf('ZfcUser\Controller\Plugin\ZfcUserAuthentication', $factoryResult);
    }
}