<?php

namespace UserAdminTest\Factory;

use Core\Service\MotFrontendAuthorisationServiceInterface;
use DvsaCommonTest\TestUtils\TestCaseTrait;
use DvsaCommonTest\TestUtils\XMock;
use UserAdmin\Factory\UserAdminSessionManagerFactory;
use UserAdmin\Service\UserAdminSessionManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserAdminSessionManagerFactoryTest extends \PHPUnit_Framework_TestCase
{
    use TestCaseTrait;

    public function testCreateService()
    {
        $mockServiceLocator = XMock::of(ServiceLocatorInterface::class, ['get']);

        $this->mockMethod($mockServiceLocator, 'get', $this->at(0), XMock::of(MotFrontendAuthorisationServiceInterface::class));

        $factory = new UserAdminSessionManagerFactory();

        $this->assertInstanceOf(
            UserAdminSessionManager::class,
            $factory->createService($mockServiceLocator)
        );
    }
}
