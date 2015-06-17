<?php
namespace AccountApiTest\Factory\Controller;

use AccountApi\Controller\PasswordUpdateController;
use AccountApi\Factory\Controller\PasswordUpdateControllerFactory;
use AccountApi\Service\TokenService;
use Doctrine\ORM\EntityManager;
use DvsaCommonTest\TestUtils\XMock;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class PasswordUpdateControllerFactoryTest
 * @package AccountTest\Factory
 */
class PasswordUpdateControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager();

        $tokenService = XMock::of(TokenService::class);
        $serviceManager->setService(TokenService::class, $tokenService);

        $entityManager = XMock::of(EntityManager::class);
        $serviceManager->setService(EntityManager::class, $entityManager);

        $plugins = $this->getMock('Zend\Mvc\Controller\ControllerManager');
        $plugins->expects($this->any())
            ->method('getServiceLocator')
            ->will($this->returnValue($serviceManager));

        // Create the factory
        $factory = new PasswordUpdateControllerFactory();
        $factoryResult = $factory->createService($plugins);

        $this->assertInstanceOf(PasswordUpdateController::class, $factoryResult);
    }
}
