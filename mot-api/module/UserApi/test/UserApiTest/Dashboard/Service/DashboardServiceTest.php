<?php

namespace UserApiTest\Dashboard\Service;

use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use UserApi\Dashboard\Service\DashboardService;

use Doctrine\ORM\EntityManager;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Enum\OrganisationBusinessRoleCode;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonApi\Service\AbstractService;
use DvsaEntities\Entity\AuthorisationForAuthorisedExaminer as AuthorisationForAuthorisedExaminerEntity;
use DvsaEntities\Entity\BusinessRoleStatus;
use DvsaEntities\Entity\Organisation;
use DvsaEntities\Entity;
use DvsaEntities\Entity\OrganisationBusinessRoleMap;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\Site;
use DvsaEntities\Entity\SiteBusinessRoleMap;
use DvsaEntities\Repository\AuthorisationForAuthorisedExaminerRepository;
use DvsaMotApi\Service\TesterService;
use NotificationApi\Service\NotificationService;
use SiteApi\Service\SiteService;
use UserApi\Dashboard\Dto\AuthorisationForAuthorisedExaminer;
use UserApi\Dashboard\Dto\DashboardData;
use UserApi\Person\Service\PersonalAuthorisationForMotTestingService;
use UserApi\SpecialNotice\Service\SpecialNoticeService;
use UserFacade\UserFacadeInterface;

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
        $authorisationService = $this->getMockWithDisabledConstructor(AuthorisationServiceInterface::class);
        $siteService = $this->getMockWithDisabledConstructor(SiteService::class);
        $userFacade = $this->getMockWithDisabledConstructor(UserFacadeInterface::class);
        $specialNoticeService = $this->getMockWithDisabledConstructor(SpecialNoticeService::class);
        $notificationService = $this->getMockWithDisabledConstructor(NotificationService::class);
        $personalAuthorisationService = $this->getMockWithDisabledConstructor(PersonalAuthorisationForMotTestingService::class);
        $testerService = $this->getMockWithDisabledConstructor(TesterService::class);
        $afaRepositoryMock = $this->getMockWithDisabledConstructor(AuthorisationForAuthorisedExaminerRepository::class);
        $this->dashboardService = new DashboardService(
            $mockEntityManager,
            $authorisationService,
            $userFacade,
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
            $ae1Id => (new AuthorisationForAuthorisedExaminerEntity())->setId($ae1Id)->setOrganisation(new Organisation())
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
                (new SiteBusinessRoleMap())->setSiteBusinessRole((new Entity\SiteBusinessRole())->setName($sitePosition))
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
        $this->assertEquals($sitePosition, $result[$ae1Id]->getSites()[0]->getPositions()[0]->getSiteBusinessRole()->getName());
    }
}