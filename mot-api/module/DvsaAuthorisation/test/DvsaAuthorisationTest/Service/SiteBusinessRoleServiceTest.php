<?php

namespace DvsaAuthorisationTest\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use DvsaAuthorisation\Service\SiteBusinessRoleService;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\BusinessRoleStatus;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\Site;
use DvsaEntities\Entity\SiteBusinessRole;
use DvsaEntities\Entity\SiteBusinessRoleMap;
use PHPUnit_Framework_Assert;

class SiteBusinessRoleServiceTest extends \PHPUnit_Framework_TestCase
{
    private $entityManagerMock;
    private $personMock;
    private $siteMock;
    private $repositoryMock;
    private $siteBusinessRoleMock;
    private $businessRoleStatusMock;

    public function setUp()
    {
        $this->entityManagerMock = XMock::of(EntityManager::class, ['getRepository', 'persist']);
        $this->personMock = XMock::of(Person::class);
        $this->siteMock = XMock::of(Site::class);
        $this->repositoryMock = XMock::of(EntityRepository::class, ['get']);
        $this->siteBusinessRoleMock = XMock::of(SiteBusinessRole::class);
        $this->businessRoleStatus = XMock::of(BusinessRoleStatus::class);
    }

    public function testSiteBusinessRoleService()
    {
        $this->entityManagerMock->expects($this->at(0))
            ->method('getRepository')
            ->willReturn($this->repositoryMock);
        $this->entityManagerMock->expects($this->at(1))
            ->method('getRepository')
            ->willReturn($this->repositoryMock);
        $this->repositoryMock->expects($this->at(0))
            ->method('get')
            ->willReturn($this->siteBusinessRoleMock);
        $this->repositoryMock->expects($this->at(1))
            ->method('get')
            ->willReturn($this->businessRoleStatusMock);
        $this->entityManagerMock->expects($this->at(2))
            ->method('persist')
            ->will(
                $this->returnCallback(
                    function ($map) {
                        PHPUnit_Framework_Assert::assertInstanceOf(SiteBusinessRoleMap::class, $map);
                        PHPUnit_Framework_Assert::assertEquals($this->personMock, $map->getPerson());
                        PHPUnit_Framework_Assert::assertEquals($this->siteMock, $map->getSite());
                        PHPUnit_Framework_Assert::assertEquals(
                            $this->siteBusinessRoleMock, $map->getSiteBusinessRole()
                        );
                        PHPUnit_Framework_Assert::assertEquals(
                            $this->businessRoleStatusMock, $map->getBusinessRoleStatus()
                        );
                    }
                )
            );
        $businessRoleCode = 'foo';
        $statusCode = 'bar';
        $siteBusinessRoleService = new SiteBusinessRoleService($this->entityManagerMock);
        $siteBusinessRoleService->addSiteBusinessRole(
            $this->personMock,
            $this->siteMock,
            $businessRoleCode,
            $statusCode
        );
    }
}
