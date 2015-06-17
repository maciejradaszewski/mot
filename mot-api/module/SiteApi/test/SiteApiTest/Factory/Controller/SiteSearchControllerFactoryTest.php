<?php
namespace SiteApiTest\Factory\Controller;

use DvsaCommonTest\TestUtils\XMock;
use SiteApi\Controller\SiteSearchController;
use SiteApi\Factory\Controller\SiteSearchControllerFactory;
use SiteApi\Service\SiteSearchService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class SiteSearchControllerFactoryTest
 * @package SiteApiTest\Factory\Controller
 */
class SiteSearchControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager();

        $tokenService = XMock::of(SiteSearchService::class);
        $serviceManager->setService(SiteSearchService::class, $tokenService);

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
