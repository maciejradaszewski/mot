<?php

namespace OrganisationApiTest\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\MotIdentity;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommonApi\Filter\XssFilter;
use DvsaCommonTest\TestUtils\TestCaseTrait;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Repository\AuthForAeStatusRepository;
use DvsaEntities\Repository\OrganisationRepository;
use DvsaEventApi\Service\EventService;
use OrganisationApi\Factory\Service\AuthorisedExaminerStatusServiceFactory;
use OrganisationApi\Service\AuthorisedExaminerStatusService;
use PHPUnit_Framework_TestCase;
use Zend\ServiceManager\ServiceManager;

class AuthorisedExaminerStatusServiceFactoryTest extends PHPUnit_Framework_TestCase
{
    use TestCaseTrait;

    public function testFactory()
    {
        $serviceManager = new ServiceManager();

        $entityManager = XMock::of(EntityManager::class);

        $serviceManager->setService(EntityManager::class, $entityManager);
        $serviceManager->setService('DvsaAuthorisationService', XMock::of(AuthorisationServiceInterface::class));
        $identityProvider = XMock::of(MotIdentityProviderInterface::class);
        $this->mockMethod($identityProvider, 'getIdentity', null, new MotIdentity(1, 'unitTest'));
        $serviceManager->setService(MotIdentityProviderInterface::class, $identityProvider);
        $serviceManager->setService(EventService::class, XMock::of(EventService::class));
        $serviceManager->setService(XssFilter::class, XMock::of(XssFilter::class));

        $this->mockMethod($entityManager, 'getRepository', $this->at(0), XMock::of(OrganisationRepository::class));
        $this->mockMethod($entityManager, 'getRepository', $this->at(1), XMock::of(AuthForAeStatusRepository::class));

        // Create the factory
        $factory = new AuthorisedExaminerStatusServiceFactory();

        $this->assertInstanceOf(AuthorisedExaminerStatusService::class, $factory->createService($serviceManager));
    }
}
