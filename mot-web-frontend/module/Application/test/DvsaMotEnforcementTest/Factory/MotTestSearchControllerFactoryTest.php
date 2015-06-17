<?php

namespace DvsaMotEnforcementTest\Factory;

use DvsaCommonTest\Bootstrap;
use DvsaMotEnforcement\Factory\MotTestSearchControllerFactory;

/**
 * Class MotTestSearchControllerFactoryTest.
 */
class MotTestSearchControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryReturnsMotTestSearchControllerInstance()
    {
        $serviceManager = Bootstrap::getServiceManager();

        $controllerManager = $this->getMock('Zend\Mvc\Controller\ControllerManager');
        $controllerManager->expects($this->any())
            ->method('getServiceLocator')
            ->will($this->returnValue($serviceManager));
        $factory = new MotTestSearchControllerFactory();

        $this->assertInstanceOf(
            'DvsaMotEnforcement\Controller\MotTestSearchController',
            $factory->createService($controllerManager)
        );
    }
}
