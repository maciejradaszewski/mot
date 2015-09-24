<?php

namespace OrganisationApiTest\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\MotIdentity;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommonApi\Filter\XssFilter;
use DvsaCommonApi\Service\ContactDetailsService;
use DvsaCommonTest\TestUtils\TestCaseTrait;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Repository\AuthForAeStatusRepository;
use DvsaEntities\Repository\AuthorisationForAuthorisedExaminerRepository;
use DvsaEntities\Repository\CompanyTypeRepository;
use DvsaEntities\Repository\OrganisationContactTypeRepository;
use DvsaEntities\Repository\OrganisationRepository;
use DvsaEntities\Repository\OrganisationTypeRepository;
use DvsaEntities\Repository\PersonRepository;
use DvsaEventApi\Service\EventService;
use OrganisationApi\Factory\Service\AuthorisedExaminerServiceFactory;
use OrganisationApi\Service\AuthorisedExaminerService;
use PHPUnit_Framework_TestCase;
use Zend\ServiceManager\ServiceManager;

class AuthorisedExaminerServiceFactoryTest extends PHPUnit_Framework_TestCase
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

        $serviceManager->setService(ContactDetailsService::class, XMock::of(ContactDetailsService::class));
        $serviceManager->setService(EventService::class, XMock::of(EventService::class));
        $serviceManager->setService(XssFilter::class, XMock::of(XssFilter::class));

        $this->mockMethod($entityManager, 'getRepository', $this->at(0), XMock::of(OrganisationRepository::class));
        $this->mockMethod($entityManager, 'getRepository', $this->at(1), XMock::of(PersonRepository::class));
        $this->mockMethod($entityManager, 'getRepository', $this->at(2), XMock::of(OrganisationTypeRepository::class));
        $this->mockMethod($entityManager, 'getRepository', $this->at(3), XMock::of(CompanyTypeRepository::class));
        $orgContactTypeRepo = XMock::of(OrganisationContactTypeRepository::class);
        $this->mockMethod($entityManager, 'getRepository', $this->at(4), $orgContactTypeRepo);
        $this->mockMethod($entityManager, 'getRepository', $this->at(5), XMock::of(AuthForAeStatusRepository::class));
        $authForAE = XMock::of(AuthorisationForAuthorisedExaminerRepository::class);
        $this->mockMethod($entityManager, 'getRepository', $this->at(6), $authForAE);

        // Create the factory
        $factory = new AuthorisedExaminerServiceFactory();

        $this->assertInstanceOf(AuthorisedExaminerService::class, $factory->createService($serviceManager));
    }
}
