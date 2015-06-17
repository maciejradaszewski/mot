<?php

namespace EquipmentApiTest\Factory\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use DvsaAuthorisation\Service\AuthorisationService;
use DvsaCommonTest\TestUtils\XMock;
use EquipmentApi\Factory\Service\EquipmentModelServiceFactory;
use EquipmentApi\Service\EquipmentModelService;
use PHPUnit_Framework_TestCase;
use Zend\ServiceManager\ServiceManager;

class EquipmentModelServiceFactoryTest extends PHPUnit_Framework_TestCase
{
    private $equipmentModelServiceFactory;
    private $entityManagerMock;
    private $serviceLocator;
    private $authenticationServiceMock;
    private $entityRepositoryMock;

    public function setUp()
    {
        $this->equipmentModelServiceFactory = new EquipmentModelServiceFactory();
        $this->entityManagerMock = XMock::of(EntityManager::class, ['getRepository']);
        $this->authenticationServiceMock = XMock::of(AuthorisationService::class);
        $this->entityRepositoryMock = XMock::of(EntityRepository::class);
        $this->serviceLocator = new ServiceManager();
        $this->serviceLocator->setService(EntityManager::class, $this->entityManagerMock);
        $this->serviceLocator->setService('DvsaAuthorisationService', $this->authenticationServiceMock);
    }

    public function testFactoryReturnsService()
    {
        $this->entityManagerMock->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->entityRepositoryMock);
        $service = $this->equipmentModelServiceFactory->createService($this->serviceLocator);
        $this->assertInstanceOf(EquipmentModelService::class, $service);
    }
}
