<?php
namespace UserAdminTest\Factory\Controller;

use DvsaCommonTest\TestUtils\XMock;
use UserAdmin\Controller\ResetAccountClaimByPostController;
use UserAdmin\Factory\Controller\ResetAccountClaimByPostControllerFactory;
use UserAdmin\Service\HelpdeskAccountAdminService;
use UserAdmin\Service\TesterQualificationStatusService;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class ResetAccountClaimByPostControllerFactoryTest
 * @package UserAdminTest\Factory\Controller
 */
class ResetAccountClaimByPostControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager();

        $plugins = $this->getMock(ControllerManager::class);
        $plugins->expects($this->any())
            ->method('getServiceLocator')
            ->will($this->returnValue($serviceManager));

        $userAccountAdminService = XMock::of(HelpdeskAccountAdminService::class);
        $serviceManager->setService(HelpdeskAccountAdminService::class, $userAccountAdminService);

        $testerQualificationStatus = XMock::of(TesterQualificationStatusService::class);
        $serviceManager->setService(TesterQualificationStatusService::class, $testerQualificationStatus);

        // Create the factory
        $factory = new ResetAccountClaimByPostControllerFactory();
        $factoryResult = $factory->createService($plugins);

        $this->assertInstanceOf(ResetAccountClaimByPostController::class, $factoryResult);
    }
}
