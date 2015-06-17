<?php
namespace AccountApiTest\Factory\Controller;

use AccountApi\Controller\SecurityQuestionController;
use AccountApi\Factory\Controller\SecurityQuestionControllerFactory;
use AccountApi\Service\SecurityQuestionService;
use Doctrine\ORM\EntityManager;
use DvsaCommonTest\TestUtils\XMock;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class SecurityQuestionControllerFactoryTest
 * @package AccountTest\Factory
 */
class SecurityQuestionControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager();

        $service = XMock::of(SecurityQuestionService::class);
        $serviceManager->setService(SecurityQuestionService::class, $service);

        $plugins = $this->getMock('Zend\Mvc\Controller\ControllerManager');
        $plugins->expects($this->any())
            ->method('getServiceLocator')
            ->will($this->returnValue($serviceManager));

        // Create the factory
        $factory = new SecurityQuestionControllerFactory();
        $factoryResult = $factory->createService($plugins);

        $this->assertInstanceOf(SecurityQuestionController::class, $factoryResult);
    }
}
