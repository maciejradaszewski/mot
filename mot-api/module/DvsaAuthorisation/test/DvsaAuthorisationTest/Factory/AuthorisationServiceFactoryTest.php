<?php

namespace DvsaAuthorisationTest\Factory;

use Doctrine\ORM\EntityManager;
use DvsaAuthorisation\Factory\AuthorisationServiceFactory;
use DvsaAuthorisation\Service\AuthorisationService;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\XMock;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class AuthorisationServiceFactoryTest
 *
 */
class AuthorisationServiceFactoryTest extends AbstractServiceTestCase
{
    /* @var AuthorisationServiceFactory $authorisationServiceFactory */
    private $authorisationServiceFactory;

    private $serviceLocator;
    private $entityManagerMock;
    private $authenticationServiceMock;

    public function setUp()
    {
        $this->authorisationServiceFactory = new AuthorisationServiceFactory();
        $this->entityManagerMock = XMock::of(EntityManager::class);
        $this->authenticationServiceMock = XMock::of(AuthenticationService::class);

        $this->serviceLocator = new ServiceManager();
        $this->serviceLocator->setService('DvsaAuthenticationService', $this->authenticationServiceMock);
        $this->serviceLocator->setService(EntityManager::class, $this->entityManagerMock);
    }

    public function testAuthorisationServiceFactoryReturnsInstance()
    {
        $this->assertInstanceOf(
            AuthorisationService::class,
            $this->authorisationServiceFactory->createService($this->serviceLocator)
        );
    }
}
