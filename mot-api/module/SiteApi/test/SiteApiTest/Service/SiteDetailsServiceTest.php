<?php

namespace SiteApiTest\Service;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\Assertion\UpdateVtsAssertion;
use DvsaCommon\Auth\MotIdentityInterface;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Dto\Site\VehicleTestingStationDto;
use DvsaCommon\Enum\SiteStatusCode;
use DvsaCommon\Exception\UnauthorisedException;
use DvsaCommonApi\Filter\XssFilter;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\TestCasePermissionTrait;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\FacilityType;
use DvsaEntities\Entity\Site;
use DvsaEntities\Entity\SiteFacility;
use DvsaEntities\Entity\SiteStatus;
use DvsaEntities\Repository\AuthorisationForTestingMotAtSiteStatusRepository;
use DvsaEntities\Repository\SiteRepository;
use DvsaEntities\Repository\SiteStatusRepository;
use DvsaEntities\Repository\SiteTestingDailyScheduleRepository;
use DvsaEntities\Repository\VehicleClassRepository;
use DvsaEventApi\Service\EventService;
use SiteApi\Service\SiteDetailsService;
use SiteApi\Service\Validator\SiteValidator;

class SiteDetailsServiceTest extends AbstractServiceTestCase
{
    use TestCasePermissionTrait;

    const SITE_ID = 1;

    /** @var SiteRepository $siteRepository */
    private $siteRepository;
    /** @var  AuthorisationServiceInterface $mockAuthService */
    private $mockAuthService;
    /** @var  UpdateVtsAssertion $updateVtsAssertion */
    private $updateVtsAssertion;
    /** @var XssFilter $mockXssFilter */
    private $mockXssFilter;
    /** @var SiteValidator $siteValidator */
    private $siteValidator;
    /** @var EventService $eventService */
    private $eventService;
    /** @var  MotIdentityInterface */
    private $mockIdentity;
    /** @var EntityManager $entityManager */
    private $entityManager;
    /** @var SiteTestingDailyScheduleRepository $scheduleRepository */
    private $scheduleRepository;
    /** @var VehicleClassRepository $vehicleClassRepository  */
    private $vehicleClassRepository;
    /** @var AuthorisationForTestingMotAtSiteStatusRepository $authForTestingMotStatusRepository*/
    private $authForTestingMotStatusRepository;
    /** @var SiteStatusRepository siteStatusRepository */
    private $siteStatusRepository;

    public function setUp()
    {
        $this->siteRepository = XMock::of(SiteRepository::class);
        $this->mockAuthService = $this->getMockAuthorizationService(false);
        $this->updateVtsAssertion = new UpdateVtsAssertion($this->mockAuthService);
        $this->mockXssFilter = $this->createXssFilterMock();
        $this->siteValidator = XMock::of(SiteValidator::class);
        $this->eventService = XMock::of(EventService::class);
        $this->mockIdentity = XMock::of(MotIdentityInterface::class);
        $this->scheduleRepository = XMock::of(SiteTestingDailyScheduleRepository::class);
        $this->entityManager = XMock::of(EntityManager::class);
        $this->vehicleClassRepository = XMock::of(VehicleClassRepository::class);
        $this->authForTestingMotStatusRepository = XMock::of(AuthorisationForTestingMotAtSiteStatusRepository::class);
        $this->siteStatusRepository = XMock::of(SiteStatusRepository::class);
    }

    private function getService()
    {
        return new SiteDetailsService(
            $this->siteRepository,
            $this->mockAuthService,
            $this->updateVtsAssertion,
            $this->mockXssFilter,
            $this->siteValidator,
            $this->eventService,
            $this->mockIdentity,
            $this->entityManager,
            $this->vehicleClassRepository,
            $this->authForTestingMotStatusRepository,
            $this->siteStatusRepository
        );
    }

    public function testExceptionThrownIfPermissionNotGrantedOnSiteDetailsUpdate()
    {
        $this->mockAssertGrantedAtSite(
            $this->mockAuthService,
            [],
            self::SITE_ID
        );

        $this->setExpectedException(UnauthorisedException::class);
        $this->getService()->update(self::SITE_ID, new VehicleTestingStationDto());
    }

    public function testIfAllBelowPermissionsAvailableButNeedsConfirmationReturnTrue()
    {
        // The VTS Assertion Service checks the following permissions
        $this->mockAssertGrantedAtSite(
            $this->mockAuthService,
            [
                PermissionAtSite::VTS_UPDATE_TESTING_FACILITIES_DETAILS,
                PermissionAtSite::VTS_UPDATE_NAME,
                PermissionAtSite::VTS_UPDATE_CORRESPONDENCE_DETAILS,
                PermissionAtSite::VTS_UPDATE_BUSINESS_DETAILS,
                PermissionAtSite::VTS_UPDATE_SITE_DETAILS
            ],
            self::SITE_ID
        );

        $this->mockService($this->siteRepository, 'get', $this->getSiteEntityMock());

        $dto = new VehicleTestingStationDto();
        $dto->setIsNeedConfirmation(true);

        $response = $this->getService()->update(self::SITE_ID, $dto);

        $this->assertTrue($response);
    }

    public function testIfAllBelowPermissionsAvailableAndConfirmedReturnsTrue()
    {
        // The VTS Assertion Service checks the following permissions
        $this->mockAssertGrantedAtSite(
            $this->mockAuthService,
            [
                PermissionAtSite::VTS_UPDATE_TESTING_FACILITIES_DETAILS,
                PermissionAtSite::VTS_UPDATE_NAME,
                PermissionAtSite::VTS_UPDATE_CORRESPONDENCE_DETAILS,
                PermissionAtSite::VTS_UPDATE_BUSINESS_DETAILS,
                PermissionAtSite::VTS_UPDATE_SITE_DETAILS
            ],
            self::SITE_ID
        );

        $siteStatusCode = SiteStatusCode::REJECTED;
        $siteStatus = (new SiteStatus())
            ->setId(1111)
            ->setCode($siteStatusCode)
        ;
        $this->mockService($this->siteStatusRepository, 'getByCode', $siteStatus);
        $this->mockService($this->siteRepository, 'get', $this->getSiteEntityMock());

        $dto = new VehicleTestingStationDto();
        $dto->setIsNeedConfirmation(false);

        $dto->setStatus($siteStatusCode);
        $dto->setName("test name edited");
        $dto->setTestClasses(["1","2"]);

        $response = $this->getService()->update(self::SITE_ID, $dto);

        $this->assertTrue($response['success']);
    }



    private function getSiteEntityMock()
    {
        $facilities = new ArrayCollection();
        $facilities->add(
            (new SiteFacility())->setId(2)
                ->setName('One Lane')->setFacilityType(
                    (new FacilityType())->setId(self::SITE_ID)->setName('OPTL')->setCode('OPTL')
                )
        );
        $facilities->add(
            (new SiteFacility())->setId(3)
                ->setName('Two Lane')->setFacilityType(
                    (new FacilityType())->setId(self::SITE_ID)->setName('TPTL')->setCode('TPTL')
                )
        );

        $siteStatus = (new SiteStatus())
            ->setId(1111)
            ->setCode(SiteStatusCode::APPROVED)
        ;
        $site = new Site();
        $site->setId(self::SITE_ID)
            ->setName('VTS Test')
            ->setFacilities($facilities)
            ->setStatus($siteStatus)
        ;

        return $site;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createXssFilterMock()
    {
        $xssFilterMock = $this
            ->getMockBuilder(XssFilter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $xssFilterMock
            ->method('filter')
            ->will($this->returnArgument(0));

        $xssFilterMock
            ->method('filterMultiple')
            ->will($this->returnArgument(0));

        return $xssFilterMock;
    }

    private function mockService($mockService, $method, $result)
    {
        $mockService->expects($this->any())
            ->method($method)
            ->willReturn($result);
    }


}