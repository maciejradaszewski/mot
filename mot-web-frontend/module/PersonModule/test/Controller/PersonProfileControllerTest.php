<?php
//
//namespace Dvsa\Mot\Frontend\PersonModuleTest\Controller;
//
//use Account\Service\SecurityQuestionService;
//use Application\Data\ApiPersonalDetails;
//use Application\Service\CatalogService;
//use Application\Service\LoggedInUserManager;
//use Core\Authorisation\Assertion\WebAcknowledgeSpecialNoticeAssertion;
//use CoreTest\Controller\AbstractFrontendControllerTestCase;
//use CoreTest\TestUtils\Identity\FrontendIdentityProviderStub;
//use Dashboard\Authorisation\ViewTradeRolesAssertion;
//use Dashboard\Controller\UserHomeController;
//use Dashboard\Data\ApiDashboardResource;
//use Dashboard\PersonStore;
//use Dashboard\Service\TradeRolesAssociationsService;
//use Dvsa\Mot\Frontend\AuthenticationModule\Model\Identity;
//use Dvsa\Mot\Frontend\PersonModule\Controller\PersonProfileController;
//use Dvsa\Mot\Frontend\Test\StubIdentityAdapter;
//use Dvsa\OpenAM\OpenAMClient;
//use DvsaClient\Entity\TesterAuthorisation;
//use DvsaClient\Entity\TesterGroupAuthorisationStatus;
//use DvsaClient\Mapper\TesterGroupAuthorisationMapper;
//use DvsaCommon\Auth\MotAuthorisationServiceInterface;
//use DvsaCommon\Auth\PermissionInSystem;
//use DvsaCommon\Enum\RoleCode;
//use DvsaCommon\Enum\VehicleClassCode;
//use DvsaCommon\HttpRestJson\Client as HttpRestJsonClient;
//use DvsaCommonTest\Bootstrap;
//use DvsaCommonTest\TestUtils\Auth\AuthorisationServiceMock;
//use DvsaCommonTest\TestUtils\XMock;
//use PHPUnit_Framework_MockObject_MockObject as MockObj;
//use UserAdmin\Service\UserAdminSessionManager;
//use Zend\Session\Container;
//use Zend\View\Model\ViewModel;
//
//class PersonProfileControllerTest extends AbstractFrontendControllerTestCase
//{
//    const USER_ID = 9999;
//
//    private static $CONFIG
//        = [
//            'dvsa_authentication' => [
//                'openAM' => [
//                    'realm' => [],
//                ],
//            ],
//        ];
//
//    /**  @var ApiPersonalDetails|MockObj */
//    private $mockPersonalDetailsSrv;
//    /**  @var |MockObj */
//    private $mockRestClient;
//    /**  @var OpenAMClient|MockObj */
//    private $mockOpenAMClient;
//    /** @var  ApiDashboardResource|MockObj */
//    private $mockDashboardSrv;
//    /** @var  LoggedInUserManager|MockObj */
//    private $loggedInUserManagerMock;
//    /** @var  PersonStore|MockObj */
//    private $mockPersonStoreSrv;
//    /** @var  CatalogService|MockObj */
//    private $mockCatalogSrv;
//    /** @var  SecurityQuestionService|MockObj */
//    private $mockSecurityQuestionSrv;
//    /** @var  UserAdminSessionManager|MockObj */
//    private $mockUserAdminSessionSrv;
//
//    /**
//     * @var TesterGroupAuthorisationMapper|MockObj
//     */
//    private $mockTesterGroupAuthorisationMapper;
//
//    /** @var AuthorisationServiceMock */
//    private $authorisationService;
//
//    /** @var FrontendIdentityProviderStub */
//    private $identityProvider;
//
//    public function setUp()
//    {
//        $sm = Bootstrap::getServiceManager();
//        $sm->setAllowOverride(true);
//
//        $this->setServiceManager($sm);
//
//        //  --  mocks   --
//        $this->mockDashboardSrv = XMock::of(ApiDashboardResource::class, ['get']);
//        $this->mockMethod($this->mockDashboardSrv, 'get', null, $this->getDashboardData());
//
//        $this->mockPersonalDetailsSrv = XMock::of(ApiPersonalDetails::class);
//
//        $this->mockTesterGroupAuthorisationMapper = XMock::of(TesterGroupAuthorisationMapper::class);
//
//        $this->mockPersonStoreSrv = XMock::of(PersonStore::class);
//
//        $this->mockOpenAMClient = $this->mockOpenAMClient();
//        $this->loggedInUserManagerMock = XMock::of(LoggedInUserManager::class);
//
//        $this->mockCatalogSrv = XMock::of(CatalogService::class);
//        $this->mockMethod($this->mockCatalogSrv, 'getCountriesOfRegistrationByCode', null, ['uk' => 'ukLong']);
//
//        $this->mockSecurityQuestionSrv = XMock::of(SecurityQuestionService::class);
//        $this->mockUserAdminSessionSrv = XMock::of(UserAdminSessionManager::class);
//
//        $catalogMockOrgData = $this->buildBusinessRolesData();
//        $catalogMockSysData = $this->buildPersonSystemCatalog();
//
//        $this->mockCatalogSrv->expects($this->any())
//            ->method("getBusinessRoles")
//            ->willReturn($catalogMockOrgData);
//
//        $this->mockCatalogSrv->expects($this->any())
//            ->method("getPersonSystemRoles")
//            ->willReturn($catalogMockSysData);
//
//        $this->authorisationService = new AuthorisationServiceMock();
//
//        $this->identityProvider = new FrontendIdentityProviderStub();
//        $this->identityProvider->setIdentity(new Identity());
//        $this->identityProvider->getIdentity()->setUserId(self::USER_ID);
//
//        //  --  create controller instance --
//        $this->setController(
//            new PersonProfileController(
//                $this->loggedInUserManagerMock,
//                $this->mockPersonalDetailsSrv,
//                $this->mockPersonStoreSrv,
//                $this->mockDashboardSrv,
//                $this->mockCatalogSrv,
//                XMock::of(WebAcknowledgeSpecialNoticeAssertion::class),
//                $this->mockSecurityQuestionSrv,
//                $this->mockUserAdminSessionSrv,
//                $this->mockTesterGroupAuthorisationMapper,
//                XMock::of(MotAuthorisationServiceInterface::class),
//                $this->mockUserAdminSessionSrv,
//                new ViewTradeRolesAssertion($this->authorisationService, $this->identityProvider),
//                XMock::of(TradeRolesAssociationsService::class)
//            )
//        );
//
//        //  --
//        parent::setUp();
//
//        $this->mockRestClient = XMock::of(HttpRestJsonClient::class, ['put']);
//        $sm->setService(HttpRestJsonClient::class, $this->mockRestClient);
//
//        $sm->setService('config', self::$CONFIG);
//
//        $this->setupAuthenticationServiceForIdentity(StubIdentityAdapter::asTester(self::USER_ID));
//    }
//
//    /**
//     * @param $hasPermissionToViewOtherProfiles
//     * @param $isViewingOwnProfile
//     * @param bool $qualificationsEmpty
//     *
//     * @dataProvider dataProviderTestSidebarIsShown
//     */
//    public function testSideBarShowsCorrectItems($hasPermissionToViewOtherProfiles, $isViewingOwnProfile, $qualificationsEmpty)
//    {
//        $this->markTestSkipped('Missing permissions service');
//        $this->setUpTestSidebarShowsCorrectItems($hasPermissionToViewOtherProfiles, $qualificationsEmpty);
//
//        $this->getResponseForAction('index', ['personId' => $isViewingOwnProfile ? self::USER_ID : 10]);
//
//        $shouldSeeAccountSecurity = $isViewingOwnProfile;
//
//        if ($shouldSeeAccountSecurity) {
//            $this->assertTrue($this->getController()->getSidebar()->hasItem('account_security'));
//        } else {
//            $this->assertFalse($this->getController()->getSidebar()->hasItem('account_security'));
//        }
//
//        if ($qualificationsEmpty) {
//            $this->assertEmpty($this->getController()->getSidebar()->getStatusBox()->getItems());
//        }
//    }
//
//    private function setUpTestSidebarShowsCorrectItems($hasPermissionToViewOtherProfiles, $qualificationsEmpty)
//    {
//        $personalDetailsData = $this->getPersonalDetailsData();
//
//        $roles = [
//            'system'        => [
//                'roles' => [RoleCode::USER]
//            ],
//            'organisations' => [],
//            'sites'         => [],
//        ];
//
//        $personalDetailsData['roles'] = $roles;
//
//        $this->mockPersonalDetailsSrv
//            ->expects($this->any())
//            ->method('getPersonalDetailsData')
//            ->willReturn($personalDetailsData);
//
//        if ($qualificationsEmpty) {
//            $this->mockTesterGroupAuthorisationMapper
//                ->expects($this->any())
//                ->method('getAuthorisation')
//                ->willReturn(new TesterAuthorisation(new TesterGroupAuthorisationStatus('ITRN', ''), new TesterGroupAuthorisationStatus('ITRN', '')));
//        } else {
//            $this->mockTesterGroupAuthorisationMapper
//                ->expects($this->any())
//                ->method('getAuthorisation')
//                ->willReturn(new TesterAuthorisation(new TesterGroupAuthorisationStatus('QLFD', 'Qualified'), new TesterGroupAuthorisationStatus('QLFD', 'Qualified')));
//        }
//
//
//        if ($hasPermissionToViewOtherProfiles) {
//            $this->authorisationService->granted(PermissionInSystem::VIEW_TRADE_ROLES_OF_ANY_USER);
//        }
//    }
//
//    public function dataProviderTestSidebarIsShown()
//    {
//        return [
//            [false, false, false],
//            [false, true, false],
//            [true, false, true],
//            [true, true, true],
//        ];
//    }
//
//
//    /**
//     * Mock for Catalog System Roles Data - if you change this please change $this->setMockRoles
//     * @return array
//     */
//    private function buildPersonSystemCatalog()
//    {
//        return [
//            [
//                'id'   => 1,
//                'code' => 'USER',
//                'name' => 'User',
//            ],
//            [
//                'id'   => 4,
//                'code' => RoleCode::AREA_OFFICE_1,
//                'name' => '',
//            ],
//            [
//                'id'   => 5,
//                'code' => RoleCode::USER,
//                'name' => '',
//            ],
//        ];
//    }
//
//    /**
//     * Mock for Catalog Business Roles Data - if you change this please change $this->setMockRoles
//     * @return array
//     */
//    private function buildBusinessRolesData()
//    {
//        return [
//            [
//                'id'   => 1,
//                'code' => 'TESTER',
//                'name' => 'Tester',
//            ],
//            [
//                'id'   => 2,
//                'code' => 'AEDM',
//                'name' => 'Authorised Examiner Designated Manager',
//            ],
//            [
//                'id'   => 3,
//                'code' => RoleCode::AUTHORISED_EXAMINER_DELEGATE,
//                'name' => '',
//            ],
//        ];
//    }
//
//
//    private function getPersonalDetailsData()
//    {
//        return [
//            'id'                   => 1,
//            'firstName'            => 'foo',
//            'middleName'           => 'bar',
//            'surname'              => 'baz',
//            'username'             => 'tester1',
//            'dateOfBirth'          => '1979-12-20',
//            'title'                => 'Mr',
//            'gender'               => 'male',
//            'addressLine1'         => 'foo',
//            'addressLine2'         => 'foo',
//            'addressLine3'         => 'foo',
//            'town'                 => 'foo',
//            'postcode'             => 'AA11 1AA',
//            'email'                => 'foo',
//            'emailConfirmation'    => null,
//            'phone'                => 1234,
//            'drivingLicenceNumber' => 'foo',
//            'drivingLicenceRegion' => 'bar',
//            'positions'            => [],
//            'roles'                => $this->setMockRoles(),
//
//        ];
//    }
//
//    private function setMockRoles()
//    {
//        return [
//            'system'        => [
//                'roles' => ['USER'],
//            ],
//            'organisations' => [10 => [
//                'name'    => 'testing',
//                'number'  => 'VTESTING',
//                'address' => '34 Test Road',
//                'roles'   => ['AEDM'],
//            ]],
//            'sites'         => [20 => [
//                'name'    => 'testing',
//                'number'  => 'VTESTING',
//                'address' => '34 Test Road',
//                'roles'   => ['TESTER'],
//            ]]
//        ];
//    }
//
//    private function getDashboardData()
//    {
//        return [
//            'hero'                   => null,
//            'permissions'            => null,
//            'specialNotice'          => [
//                'daysLeftToView' => null,
//                'unreadCount'    => null,
//                'overdueCount'   => null,
//            ],
//            'overdueSpecialNotices' => array_combine(VehicleClassCode::getAll(), array_fill(0, count(VehicleClassCode::getAll()), 0)),
//            'notifications'          => [],
//            'inProgressTestNumber'   => null,
//            'inProgressTestTypeCode' => null,
//            'authorisedExaminers'    => [],
//        ];
//    }
//
//
//    /**
//     * @return UserHomeController
//     */
//    protected function getController()
//    {
//        return parent::getController();
//    }
//}
