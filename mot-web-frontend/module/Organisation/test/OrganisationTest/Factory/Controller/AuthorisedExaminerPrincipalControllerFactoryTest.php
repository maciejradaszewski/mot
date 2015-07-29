<?php

namespace OrganisationTest\Factory\Controller;

use Core\Service\MotFrontendAuthorisationServiceInterface;
use DvsaClient\MapperFactory;
use DvsaCommonTest\TestUtils\XMock;
use Organisation\Controller\AuthorisedExaminerPrincipalController;
use Organisation\Factory\Controller\AuthorisedExaminerPrincipalControllerFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;
use \PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Class AuthorisedExaminerPrincipalControllerFactoryTest
 * @package OrganisationTest\Factory\Controller
 */
class AuthorisedExaminerPrincipalControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager();

        $serviceManager->setService("AuthorisationService", XMock::of(MotFrontendAuthorisationServiceInterface::class));
        $serviceManager->setService(MapperFactory::class, XMock::of(MapperFactory::class));

        /** @var ServiceLocatorInterface|MockObject $plugins */
        $plugins = $this->getMock('Zend\Mvc\Controller\ControllerManager');
        $plugins->expects($this->any())
            ->method('getServiceLocator')
            ->willReturn($serviceManager);

        //  --  check   --
        $this->assertInstanceOf(
            AuthorisedExaminerPrincipalController::class,
            (new AuthorisedExaminerPrincipalControllerFactory())->createService($plugins)
        );
    }
}
