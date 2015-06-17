<?php
namespace SiteTest\Factory\Controller;

use DvsaClient\MapperFactory;
use DvsaCommonTest\TestUtils\XMock;
use Site\Controller\SiteSearchController;
use Site\Factory\Controller\SiteSearchControllerFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class SiteSearchControllerFactoryTest
 * @package SiteTest\Factory\Controller
 */
class SiteSearchControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager();

        $service = XMock::of(MapperFactory::class);
        $serviceManager->setService(MapperFactory::class, $service);

        $plugins = $this->getMock('Zend\Mvc\Controller\ControllerManager');
        $plugins->expects($this->any())
            ->method('getServiceLocator')
            ->will($this->returnValue($serviceManager));

        // Create the factory
        $factory = new SiteSearchControllerFactory();
        $factoryResult = $factory->createService($plugins);

        $this->assertInstanceOf(SiteSearchController::class, $factoryResult);
    }
}
