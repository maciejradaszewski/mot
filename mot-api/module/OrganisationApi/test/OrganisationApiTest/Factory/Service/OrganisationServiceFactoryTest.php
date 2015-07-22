<?php

namespace OrganisationApiTest\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Repository\OrganisationRepository;
use OrganisationApi\Factory\Service\OrganisationServiceFactory;
use OrganisationApi\Service\OrganisationService;
use PHPUnit_Framework_TestCase;
use Zend\ServiceManager\ServiceManager;

class OrganisationServiceFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager();
        $entityManager = XMock::of(EntityManager::class);
        $organisation = XMock::of(OrganisationRepository::class);

        $serviceManager->setService(EntityManager::class, $entityManager);

        $entityManager->expects($this->at(0))->method('getRepository')->willReturn($organisation);

        // Create the factory
        $factory = new OrganisationServiceFactory();

        $this->assertInstanceOf(OrganisationService::class, $factory->createService($serviceManager));
    }
}
