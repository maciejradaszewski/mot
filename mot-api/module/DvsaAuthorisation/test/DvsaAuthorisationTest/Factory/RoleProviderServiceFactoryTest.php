<?php

namespace DvsaAuthorisationTest\Factory;

use Doctrine\ORM\EntityManager;
use DvsaAuthorisation\Factory\RoleProviderServiceFactory;
use DvsaAuthorisation\Service\RoleProviderService;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\XMock;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class RoleProviderServiceFactoryTest
 *
 */
class RoleProviderServiceFactoryTest extends AbstractServiceTestCase
{
    /* @var RoleProviderServiceFactory $roleProviderServiceFactory */
    private $roleProviderServiceFactory;

    private $serviceLocator;
    private $entityManagerMock;

    public function setUp()
    {
        $this->roleProviderServiceFactory = new RoleProviderServiceFactory();
        $this->entityManagerMock = XMock::of(EntityManager::class);
        $this->serviceLocator = new ServiceManager();
        $this->serviceLocator->setService(EntityManager::class, $this->entityManagerMock);
    }

    public function testRoleProviderServiceFactoryReturnsInstance()
    {
        $this->assertInstanceOf(
            RoleProviderService::class,
            $this->roleProviderServiceFactory->createService($this->serviceLocator)
        );
    }
}
