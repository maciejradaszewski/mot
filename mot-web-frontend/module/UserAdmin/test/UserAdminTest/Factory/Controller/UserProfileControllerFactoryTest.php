<?php

namespace UserAdminTest\Factory\Controller;

use Core\Service\MotFrontendAuthorisationServiceInterface;
use DvsaCommonTest\TestUtils\XMock;
use UserAdmin\Controller\UserProfileController;
use UserAdmin\Service\HelpdeskAccountAdminService;
use UserAdmin\Service\TesterQualificationStatusService;
use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Controller\ControllerManager;
use UserAdmin\Factory\Controller\UserProfileControllerFactory;

/**
 * Test for {@link \UserAdmin\Factory\Controller\UserProfileControllerFactory}.
 */
class UserProfileControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceManager = new ServiceManager();

        $userAccountAdminService = XMock::of(HelpdeskAccountAdminService::class);
        $serviceManager->setService(HelpdeskAccountAdminService::class, $userAccountAdminService);
        $authorisationService = XMock::of(MotFrontendAuthorisationServiceInterface::class);
        $serviceManager->setService("AuthorisationService", $authorisationService);

        $testerQualificationStatus = XMock::of(TesterQualificationStatusService::class);
        $serviceManager->setService(TesterQualificationStatusService::class, $testerQualificationStatus);

        $plugins = $this->getMock(ControllerManager::class);
        $plugins->expects($this->any())
            ->method('getServiceLocator')
            ->will($this->returnValue($serviceManager));

        $factory = new UserProfileControllerFactory();

        $factoryResult = $factory->createService($plugins);

        $this->assertInstanceOf(UserProfileController::class, $factoryResult);
    }
}
