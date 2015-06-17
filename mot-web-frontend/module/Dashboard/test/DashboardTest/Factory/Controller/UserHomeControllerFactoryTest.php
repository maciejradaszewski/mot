<?php

namespace DashboardTest\Factory\Controller;

use Account\Service\SecurityQuestionService;
use Application\Data\ApiPersonalDetails;
use Application\Service\CatalogService;
use Application\Service\LoggedInUserManager;
use Dashboard\Controller\UserHomeController;
use Dashboard\Data\ApiDashboardResource;
use Dashboard\Factory\Controller\UserHomeControllerFactory;
use Dashboard\PersonStore;
use DvsaCommonTest\TestUtils\XMock;
use UserAdmin\Service\UserAdminSessionManager;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;
use Core\Authorisation\Assertion\WebAcknowledgeSpecialNoticeAssertion;

/**
 * Class PasswordResetControllerFactoryTest
 * @package AccountTest\Factory
 */
class UserHomeControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager();

        $serviceManager->setService(ApiPersonalDetails::class, XMock::of(ApiPersonalDetails::class));
        $serviceManager->setService(ApiDashboardResource::class, XMock::of(ApiDashboardResource::class));
        $serviceManager->setService(PersonStore::class, XMock::of(PersonStore::class));
        $serviceManager->setService('LoggedInUserManager', XMock::of(LoggedInUserManager::class));
        $serviceManager->setService('CatalogService', XMock::of(CatalogService::class));
        $serviceManager->setService(
            WebAcknowledgeSpecialNoticeAssertion::class,
            XMock::of(WebAcknowledgeSpecialNoticeAssertion::class)
        );
        $serviceManager->setService(SecurityQuestionService::class, XMock::of(SecurityQuestionService::class));
        $serviceManager->setService(UserAdminSessionManager::class, XMock::of(UserAdminSessionManager::class));

        $plugins = XMock::of(\Zend\Mvc\Controller\ControllerManager::class, ['getServiceLocator']);
        $plugins->expects($this->any())
            ->method('getServiceLocator')
            ->willReturn($serviceManager);

        // Create the factory
        $factory = new UserHomeControllerFactory();
        $factoryResult = $factory->createService($plugins);

        $this->assertInstanceOf(UserHomeController::class, $factoryResult);
    }
}
