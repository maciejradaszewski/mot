<?php

namespace VehicleTest\Factory;

use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\TestUtils\XMock;
use Vehicle\Controller\VehicleController;
use Vehicle\Factory\VehicleControllerFactory;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class VehicleControllerFactoryTest extends \PHPUnit_Framework_TestCase {

    public function testFactory()
    {
        $serviceManager = new ServiceManager();

        $paramObfuscator = XMock::of(ParamObfuscator::class);
        $serviceManager->setService(ParamObfuscator::class, $paramObfuscator);

        $plugins = $this->getMock('Zend\Mvc\Controller\ControllerManager');
        $plugins->expects($this->any())
                ->method('getServiceLocator')
                ->will($this->returnValue($serviceManager));

        // Create the factory
        $factory = new VehicleControllerFactory();
        $factoryResult = $factory->createService($plugins);

        $this->assertInstanceOf(VehicleController::class, $factoryResult);
    }

}
