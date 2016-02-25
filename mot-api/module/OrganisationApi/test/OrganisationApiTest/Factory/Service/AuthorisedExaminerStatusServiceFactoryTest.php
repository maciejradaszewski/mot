<?php

namespace OrganisationApiTest\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommonTest\TestUtils\TestCaseTrait;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Repository\SiteRepository;
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

        $this->mockMethod($entityManager, 'getRepository', $this->at(0), XMock::of(SiteRepository::class));

        // Create the factory
        $factory = new AuthorisedExaminerStatusServiceFactory();

        $this->assertInstanceOf(AuthorisedExaminerStatusService::class, $factory->createService($serviceManager));
    }
}
