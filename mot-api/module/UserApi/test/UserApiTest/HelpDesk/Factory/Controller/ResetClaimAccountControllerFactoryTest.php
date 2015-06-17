<?php

namespace UserApiTest\HelpDesk\Factory\Controller;

use Doctrine\ORM\EntityManager;
use DvsaCommonTest\TestUtils\XMock;
use UserApi\HelpDesk\Controller\ResetClaimAccountController;
use UserApi\HelpDesk\Factory\Controller\ResetClaimAccountControllerFactory;
use UserApi\HelpDesk\Service\ResetClaimAccountService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class ResetClaimAccountControllerFactoryTest
 * @package UserApiTest\HelpDesk\Factory\Controller
 */
class ResetClaimAccountControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager();

        $service = XMock::of(ResetClaimAccountService::class);
        $serviceManager->setService(ResetClaimAccountService::class, $service);

        $entityManager = XMock::of(EntityManager::class);
        $serviceManager->setService(EntityManager::class, $entityManager);

        $plugins = $this->getMock('Zend\Mvc\Controller\ControllerManager');
        $plugins->expects($this->any())
            ->method('getServiceLocator')
            ->will($this->returnValue($serviceManager));

        // Create the factory
        $factory = new ResetClaimAccountControllerFactory();
        $factoryResult = $factory->createService($plugins);

        $this->assertInstanceOf(ResetClaimAccountController::class, $factoryResult);
    }
}
