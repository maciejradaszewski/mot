<?php

namespace OrganisationApiTest\Factory\Controller;

use Doctrine\ORM\EntityManager;
use DvsaCommonTest\TestUtils\XMock;
use OrganisationApi\Controller\AuthorisedExaminerController;
use OrganisationApi\Factory\Controller\AuthorisedExaminerControllerFactory;
use OrganisationApi\Service\AuthorisedExaminerService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class AuthorisedExaminerControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager();

        $serviceManager->setService(AuthorisedExaminerService::class, XMock::of(AuthorisedExaminerService::class));

        $plugins = $this->getMock('Zend\Mvc\Controller\ControllerManager');
        $plugins->expects($this->any())
            ->method('getServiceLocator')
            ->willReturn($serviceManager);

        // Create the factory
        $factory = new AuthorisedExaminerControllerFactory();
        $factoryResult = $factory->createService($plugins);

        $this->assertInstanceOf(AuthorisedExaminerController::class, $factoryResult);
    }
}
