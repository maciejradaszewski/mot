<?php

namespace UserApiTest\Person\Factory\Controller;

use DvsaCommon\Validator\UsernameValidator;
use DvsaCommonTest\Bootstrap;
use UserApi\Person\Controller\PersonByLoginController;
use UserApi\Person\Factory\Controller\PersonByLoginControllerFactory;

/**
 * Class PersonByLoginControllerFactoryTest.
 */
class PersonByLoginControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryReturnsPersonByLoginControllerInstance()
    {
        $serviceManager = Bootstrap::getServiceManager();

        $usernameValidatorMock = $this
            ->getMockBuilder(UsernameValidator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $serviceManager->setService(UsernameValidator::class, $usernameValidatorMock);

        $controllerManager = $this->getMock('Zend\Mvc\Controller\ControllerManager');
        $controllerManager->expects($this->any())
            ->method('getServiceLocator')
            ->will($this->returnValue($serviceManager));
        $factory = new PersonByLoginControllerFactory();

        $this->assertInstanceOf(PersonByLoginController::class, $factory->createService($controllerManager));
    }
}
