<?php

namespace PersonApi\Service;

use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use Doctrine\ORM\EntityManager;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaEntities\Entity\AuthorisationForAuthorisedExaminer as AuthorisationForAuthorisedExaminerEntity;
use DvsaEntities\Entity\Organisation;
use DvsaEntities\Entity;
use DvsaEntities\Entity\Site;
use DvsaEntities\Entity\SiteBusinessRoleMap;
use DvsaEntities\Repository\AuthorisationForAuthorisedExaminerRepository;
use DvsaMotApi\Service\TesterService;
use NotificationApi\Service\NotificationService;
use SiteApi\Service\SiteService;
use UserApi\SpecialNotice\Service\SpecialNoticeService;

/**
 * Tests for DashboardService
 */
class DashboardServiceTest extends AbstractServiceTestCase
{
    /**
     * @var DashboardService
     */
    private $dashboardService;

    public function setUp()
    {
        $mockEntityManager = $this->getMockEntityManager();
        /** @var AuthorisationServiceInterface $authorisationService */
        $authorisationService = $this->getMockWithDisabledConstructor(AuthorisationServiceInterface::class);
        /** @var SiteService $siteService */
        $siteService = $this->getMockWithDisabledConstructor(SiteService::class);
        /** @var SpecialNoticeService $specialNoticeService */
        $specialNoticeService = $this->getMockWithDisabledConstructor(SpecialNoticeService::class);
        /** @var NotificationService $notificationService */
        $notificationService = $this->getMockWithDisabledConstructor(NotificationService::class);
        /** @var PersonalAuthorisationForMotTestingService $personalAuthorisationService */
        $personalAuthorisationService = $this->getMockWithDisabledConstructor(
            PersonalAuthorisationForMotTestingService::class
        );
        /** @var TesterService $testerService */
        $testerService = $this->getMockWithDisabledConstructor(TesterService::class);
        /** @var AuthorisationForAuthorisedExaminerRepository $afaRepositoryMock */
        $afaRepositoryMock = $this->getMockWithDisabledConstructor(AuthorisationForAuthorisedExaminerRepository::class);

        $this->dashboardService = new DashboardService(
            $mockEntityManager,
            $authorisationService,
            $siteService,
            $specialNoticeService,
            $notificationService,
            $personalAuthorisationService,
            $testerService,
            $afaRepositoryMock
        );
    }

    public function testGetAesWithSitesAndPositions_empty()
    {
        //given
        $aesById = [];
        $personId = 1;
        $sitesByAe = [];
        $positionsBySite = [];
        $aesPositionNames = [];

        //when
        $result = $this->dashboardService->getAesWithSitesAndPositions(
            $aesById, $personId, $sitesByAe, $positionsBySite, $aesPositionNames
        );

        //then
        $this->assertEquals([], $result);
    }

    public function testGetAesWithSitesAndPositions_mapsPositions()
    {
        //given
        $ae1Id = 123;
        $aesById = [
            $ae1Id => (new AuthorisationForAuthorisedExaminerEntity())
                ->setId($ae1Id)->setOrganisation(new Organisation())
        ];
        $personId = 18765;
        $site1Id = 12345;
        $sitesByAe = [
            $ae1Id => [
                (new Site())->setId($site1Id)
            ]
        ];
        $sitePosition = 'NOBODY';
        $positionsBySite = [
            $site1Id => [
                (new SiteBusinessRoleMap())
                    ->setSiteBusinessRole((new Entity\SiteBusinessRole())->setName($sitePosition))
            ]
        ];
        $ae1Position = 'GOD';
        $aesPositionNames = [
            $ae1Id => $ae1Position
        ];

        //when
        $result = $this->dashboardService->getAesWithSitesAndPositions(
            $aesById, $personId, $sitesByAe, $positionsBySite, $aesPositionNames
        );

        //then
        $this->assertEquals($ae1Position, $result[$ae1Id]->getPosition());
        $this->assertEquals(
            $sitePosition,
            $result[$ae1Id]->getSites()[0]->getPositions()[0]->getSiteBusinessRole()->getName()
        );
    }
}