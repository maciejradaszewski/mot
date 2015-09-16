<?php

namespace DashboardTest\Factory\Controller;

use Doctrine\ORM\EntityManager;
use DvsaCommonTest\TestUtils\XMock;
use Dashboard\Factory\Controller\PasswordControllerFactory;
use Dashboard\Controller\PasswordController;
use Dashboard\Service\PasswordService;
use Core\Service\MotFrontendIdentityProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;
use Dvsa\Mot\Frontend\AuthenticationModule\Model\MotFrontendIdentityInterface;

class PasswordControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager();

        $identity = XMock::of(MotFrontendIdentityInterface::class, ['getUsername']);
        $identityProvider = XMock::of(MotFrontendIdentityProviderInterface::class);
        $identityProvider
            ->expects($this->any())
            ->method("getIdentity")
            ->willReturn($identity);

        $serviceManager->setService('MotIdentityProvider', $identityProvider);

        $entityManager = XMock::of(PasswordService::class);
        $serviceManager->setService(PasswordService::class, $entityManager);

        $plugins = $this->getMock('Zend\Mvc\Controller\ControllerManager');
        $plugins->expects($this->any())
            ->method('getServiceLocator')
            ->will($this->returnValue($serviceManager));

        // Create the factory
        $factory = new PasswordControllerFactory();
        $factoryResult = $factory->createService($plugins);

        $this->assertInstanceOf(PasswordController::class, $factoryResult);
    }
}
