<?php

namespace OrganisationApiTest\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommonApi\Filter\XssFilter;
use DvsaCommonApi\Service\ContactDetailsService;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Repository\AuthForAeStatusRepository;
use DvsaEntities\Repository\AuthorisationForAuthorisedExaminerRepository;
use DvsaEntities\Repository\CompanyTypeRepository;
use DvsaEntities\Repository\OrganisationContactTypeRepository;
use DvsaEntities\Repository\OrganisationRepository;
use DvsaEntities\Repository\OrganisationTypeRepository;
use DvsaEntities\Repository\PersonRepository;
use DvsaEntities\Repository\SiteRepository;
use OrganisationApi\Factory\Service\AuthorisedExaminerServiceFactory;
use OrganisationApi\Service\AuthorisedExaminerService;
use OrganisationApi\Service\OrganisationService;
use PHPUnit_Framework_TestCase;
use Zend\ServiceManager\ServiceManager;

class AuthorisedExaminerServiceFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager();
        $entityManager = XMock::of(EntityManager::class);
        $dvsaAuthorisationService = XMock::of(AuthorisationServiceInterface::class);
        $organisationService = XMock::of(OrganisationService::class);
        $contactDetailsService = XMock::of(ContactDetailsService::class);
        $xssFilter = XMock::of(XssFilter::class);

        $organisationType = XMock::of(OrganisationTypeRepository::class);
        $companyType = XMock::of(CompanyTypeRepository::class);
        $organisation = XMock::of(OrganisationRepository::class);
        $person = XMock::of(PersonRepository::class);
        $organisationContactType = XMock::of(OrganisationContactTypeRepository::class);
        $authForAeStatus = XMock::of(AuthForAeStatusRepository::class);
        $authorisationForAuthorisedExaminer = XMock::of(AuthorisationForAuthorisedExaminerRepository::class);
        $siteType = XMock::of(SiteRepository::class);

        $serviceManager->setService(EntityManager::class, $entityManager);
        $serviceManager->setService('DvsaAuthorisationService', $dvsaAuthorisationService);
        $serviceManager->setService(OrganisationService::class, $organisationService);
        $serviceManager->setService(ContactDetailsService::class, $contactDetailsService);
        $serviceManager->setService(XssFilter::class, $xssFilter);

        $entityManager->expects($this->at(0))->method('getRepository')->willReturn($organisationType);
        $entityManager->expects($this->at(1))->method('getRepository')->willReturn($companyType);
        $entityManager->expects($this->at(2))->method('getRepository')->willReturn($organisation);
        $entityManager->expects($this->at(3))->method('getRepository')->willReturn($person);
        $entityManager->expects($this->at(4))->method('getRepository')->willReturn($organisationContactType);
        $entityManager->expects($this->at(5))->method('getRepository')->willReturn($authForAeStatus);
        $entityManager->expects($this->at(6))->method('getRepository')->willReturn($authorisationForAuthorisedExaminer);
        $entityManager->expects($this->at(7))->method('getRepository')->willReturn($siteType);

        // Create the factory
        $factory = new AuthorisedExaminerServiceFactory();

        $this->assertInstanceOf(AuthorisedExaminerService::class, $factory->createService($serviceManager));
    }
}
