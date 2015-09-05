<?php

namespace OrganisationApiTest\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommonTest\TestUtils\TestCaseTrait;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Repository\OrganisationRepository;
use OrganisationApi\Factory\Service\SiteServiceFactory;
use OrganisationApi\Service\Mapper\SiteMapper;
use OrganisationApi\Service\SiteService;
use Zend\ServiceManager\ServiceManager;

class SiteServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    use TestCaseTrait;

    public function testFactory()
    {
        $entityManager = XMock::of(EntityManager::class);
        $this->mockMethod($entityManager, 'getRepository', $this->at(0), XMock::of(OrganisationRepository::class));

        $serviceManager = new ServiceManager();
        $serviceManager->setService(EntityManager::class, $entityManager);
        $serviceManager->setService('DvsaAuthorisationService', XMock::of(AuthorisationServiceInterface::class));
        $serviceManager->setService(SiteMapper::class, XMock::of(SiteMapper::class));

        // Create the factory
        $factory = new SiteServiceFactory();

        $this->assertInstanceOf(SiteService::class, $factory->createService($serviceManager));
    }
}
