<?php

namespace DashboardTest\Controller;

use Account\Service\SecurityQuestionService;
use Application\Data\ApiPersonalDetails;
use Application\Helper\PrgHelper;
use Application\Service\CatalogService;
use Application\Service\LoggedInUserManager;
use CoreTest\Controller\AbstractFrontendControllerTestCase;
use Dashboard\Controller\UserHomeController;
use Dashboard\Data\ApiDashboardResource;
use Dashboard\Model\PersonalDetails;
use Dashboard\PersonStore;
use Dvsa\OpenAM\Exception\OpenAMClientException;
use Dvsa\OpenAM\Exception\OpenAMUnauthorisedException;
use Dvsa\OpenAM\OpenAMClient;
use Dvsa\OpenAM\OpenAMClientInterface;
use DvsaCommon\Auth\NotLoggedInException;
use DvsaCommon\HttpRestJson\Client as HttpRestJsonClient;
use DvsaCommon\HttpRestJson\Exception\GeneralRestException;
use DvsaCommon\HttpRestJson\Exception\NotFoundException;
use DvsaCommon\HttpRestJson\Exception\ValidationException;
use DvsaCommon\UrlBuilder\AccountUrlBuilderWeb;
use DvsaCommon\UrlBuilder\PersonUrlBuilder;
use DvsaCommon\UrlBuilder\PersonUrlBuilderWeb;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\Controller\StubIdentityAdapter;
use DvsaCommonTest\TestUtils\XMock;
use PHPUnit_Framework_MockObject_MockObject as MockObj;
use UserAdmin\Service\UserAdminSessionManager;
use Zend\Session\Container;
use Zend\Stdlib\Parameters;
use Zend\View\Model\ViewModel;
use Core\Authorisation\Assertion\WebAcknowledgeSpecialNoticeAssertion;
use DvsaClient\Mapper\TesterGroupAuthorisationMapper;

class UserHomeControllerTest extends AbstractFrontendControllerTestCase
{
    const USER_ID = 9999;

    private static $CONFIG
        = [
            'dvsa_authentication' => [
                'openAM' => [
                    'realm' => [],
                ],
            ],
        ];

    /**  @var ApiPersonalDetails|MockObj */
    private $mockPersonalDetailsSrv;
    /**  @var |MockObj */
    private $mockRestClient;
    /**  @var OpenAMClient|MockObj */
    private $mockOpenAMClient;
    /** @var  ApiDashboardResource|MockObj */
    private $mockDashboardSrv;
    /** @var  LoggedInUserManager|MockObj */
    private $loggedInUserManagerMock;
    /** @var  PersonStore|MockObj */
    private $mockPersonStoreSrv;
    /** @var  CatalogService|MockObj */
    private $mockCatalogSrv;
    /** @var  SecurityQuestionService|MockObj */
    private $mockSecurityQuestionSrv;
    /** @var  UserAdminSessionManager|MockObj */
    private $mockUserAdminSessionSrv;


    public function setUp()
    {
        $sm = Bootstrap::getServiceManager();
        $sm->setAllowOverride(true);

        $this->setServiceManager($sm);

        //  --  mocks   --
        $this->mockDashboardSrv = XMock::of(ApiDashboardResource::class, ['get']);
        $this->mockMethod($this->mockDashboardSrv, 'get', null, $this->getDashboarhData());

        $this->mockPersonalDetailsSrv = XMock::of(ApiPersonalDetails::class);
        $this->mockMethod(
            $this->mockPersonalDetailsSrv, 'getPersonalDetailsData', null, $this->getPersonalDetailsData()
        );

        $this->mockPersonStoreSrv = XMock::of(PersonStore::class);

        $this->mockOpenAMClient = $this->mockOpenAMClient();
        $this->loggedInUserManagerMock = XMock::of(LoggedInUserManager::class);

        $this->mockCatalogSrv = XMock::of(CatalogService::class);
        $this->mockMethod($this->mockCatalogSrv, 'getCountriesOfRegistrationByCode', null, ['uk' => 'ukLong']);

        $this->mockSecurityQuestionSrv = XMock::of(SecurityQuestionService::class);
        $this->mockUserAdminSessionSrv = XMock::of(UserAdminSessionManager::class);

        //  --  create controller instance --
        $this->setController(
            new UserHomeController(
                $this->loggedInUserManagerMock,
                $this->mockPersonalDetailsSrv,
                $this->mockPersonStoreSrv,
                $this->mockDashboardSrv,
                $this->mockCatalogSrv,
                XMock::of(WebAcknowledgeSpecialNoticeAssertion::class),
                $this->mockSecurityQuestionSrv,
                $this->mockUserAdminSessionSrv,
                XMock::of(TesterGroupAuthorisationMapper::class)
            )
        );

        //  --
        parent::setUp();

        $this->mockRestClient = XMock::of(HttpRestJsonClient::class, ['put']);
        $sm->setService(HttpRestJsonClient::class, $this->mockRestClient);

        $sm->setService('config', self::$CONFIG);

        $this->setupAuthenticationServiceForIdentity(StubIdentityAdapter::asTester(self::USER_ID));
    }


    /**
     * Test has user access to page or not with/out auth and permission
     *
     * @param string $action Request action
     * @param array $params Action parameters
     * @param boolean $isAuth Can user access page without Auth
     * @param array $permissions User has permissions
     * @param boolean $expectCanAccess Expect user has or not access to page
     * @param null $expectedUrl
     * @param string $expectException
     * @param null $expectErrMsg
     * @param bool $isUserPassSecurity
     *
     * @dataProvider dataProviderTestCanAccessHasRight
     */
    public function testCanAccessHasRight(
        $action,
        $params = [],
        $isAuth = true,
        $permissions = [],
        $expectCanAccess = true,
        $expectedUrl = null,
        $expectException = 'Exception',
        $expectErrMsg = null,
        $isUserPassSecurity = true
    ) {
        if (!$isAuth) {
            $this->getAuthenticationServiceMockForFailure();

            if (!$expectCanAccess) {
                $this->setExpectedException(NotLoggedInException::class);
            }

            $this->getResponseForAction($action, $params);
        } else {
            $this->setupAuthenticationServiceForIdentity(StubIdentityAdapter::asTester());
            $this->setupAuthorizationService($permissions);

            if (!$expectCanAccess) {
                $this->setExpectedException($expectException, ($expectErrMsg ? $expectErrMsg : ''));
            }

            $this->mockUserAdminSessionSrv->expects($this->any())
                ->method('isUserAuthenticated')
                ->willReturn($isUserPassSecurity);

            $method = 'get';
            $this->getResultForAction2($method, $action, $params);

            if ($expectedUrl) {
                $this->assertRedirectLocation2($expectedUrl);
            } else {
                $this->assertResponseStatus(self::HTTP_OK_CODE);
            }
        }
    }

    public function dataProviderTestCanAccessHasRight()
    {
        return [
            [
                'action'          => 'userHome',
                'params'          => [],
                'isAuth'          => true,
                'permissions'     => [],
                'expectCanAccess' => true,
            ],
            ['profile', [], true, [], true],
            ['securitySettings', [], true, [], true],
            ['securitySettings', [], true, [], true, '/profile/security-question', 'Exception', null, false],
//            ['edit', [], true, [], true],
        ];
    }


    /**
     * @dataProvider dataProviderTestActionsResultAndAccess
     */
    public function testActionsResultAndAccess($method, $action, $params, $mocks, $expect)
    {
        $result = null;

        $session = new Container('prgHelperSession');
        $session->offsetSet('testToken', 'redirectUrl');

        if ($mocks !== null) {
            foreach ($mocks as $mock) {
                $this->mockMethod(
                    $this->{$mock['class']}, $mock['method'], $this->once(), $mock['result'], $mock['params']
                );
            }
        }

        //  --  set expected exception  --
        if (!empty($expect['exception'])) {
            $exception = $expect['exception'];
            $this->setExpectedException(
                $exception['class'], $exception['message'], ArrayUtils::tryGet($exception, 'code')
            );
        }

        $result = $this->getResultForAction2(
            $method, $action, ArrayUtils::tryGet($params, 'route'), null, ArrayUtils::tryGet($params, 'post')
        );

        //  --  check   --
        if (!empty($expect['viewModel'])) {
            $this->assertInstanceOf(ViewModel::class, $result);
            $this->assertResponseStatus(self::HTTP_OK_CODE);
        }

        if (!empty($expect['result'])) {
            $this->assertResponseStatus(self::HTTP_OK_CODE);
            $this->assertEquals($expect['result'], $result);
        }

        if (!empty($expect['errors'])) {
            $form = $result->getVariable('viewModel');

            foreach ($expect['errors'] as $field => $error) {
                $this->assertEquals($error, $form->getError($field));
            }
        }

        if (!empty($expect['flashError'])) {
            $this->assertEquals(
                $expect['flashError'],
                $this->getController()->flashMessenger()->getCurrentErrorMessages()[0]
            );
        }

        if (!empty($expect['url'])) {
            $this->assertRedirectLocation2($expect['url']);
        }
    }


    public function dataProviderTestActionsResultAndAccess()
    {
        $exceptionMessage = 'Some exception happens in service';

        $newPin = 'unit_newPin';

        return [
            //  --  edit: success update    --
//            [
//                'method' => 'post',
//                'action' => 'edit',
//                'params' => [
//                    'post' => [
//                        'fieldX'       => 'unitA1',
//                    ],
//                ],
//                'mocks'  => [
//                    [
//                        'class'  => 'mockPersonStoreSrv',
//                        'method' => 'update',
//                        'params' => [
//                            self::USER_ID,
//                            ['fieldX' => 'unitA1']
//                        ],
//                        'result' => true,
//                    ],
//
//                ],
//                'expect' => [
//                    'url' => PersonUrlBuilderWeb::profile(),
//                ],
//            ],
//            //  --  edit: api thrown exception  --
//            [
//                'method' => 'post',
//                'action' => 'edit',
//                'params' => [
//                    'post' => [
//                        'phoneNumber'  => '00123',
//                    ],
//                ],
//                'mocks'  => [
//                    [
//                        'class'  => 'mockPersonStoreSrv',
//                        'method' => 'update',
//                        'params' => [
//                            self::USER_ID,
//                            ['phoneNumber' => '00123']
//                        ],
//                        'result' => new ValidationException(
//                            '/', 'post', [], 999, [['displayMessage' => $exceptionMessage]]
//                        ),
//                    ],
//                ],
//                'expect' => [
//                    'flashError' => $exceptionMessage,
//                ],
//            ],

            //  --  securitySettings: success  --
            [
                'method' => 'post',
                'action' => 'securitySettings',
                'params' => [
                    'post' => [],
                ],
                'mocks'  => [
                    [
                        'class'  => 'mockRestClient',
                        'method' => 'put',
                        'params' => [
                            PersonUrlBuilder::resetPin(self::USER_ID),
                            null
                        ],
                        'result' => ['data' => ['pin' => $newPin]],
                    ],
                    [
                        'class'  => 'mockUserAdminSessionSrv',
                        'method' => 'isUserAuthenticated',
                        'params' => [],
                        'result' => true,
                    ],
                ],
                'expect' => [
                    'result' => [
                        'pin'      => $newPin,
                        'fullName' => 'Mr foo bar baz',
                        'userId'   => self::USER_ID,
                        'config'   => self::$CONFIG,
                    ],
                ],
            ],
            //  --  securitySettings: fail GeneralRestException --
            [
                'method' => 'post',
                'action' => 'securitySettings',
                'params' => [
                    'post' => [],
                ],
                'mocks'  => [
                    [
                        'class'  => 'mockRestClient',
                        'method' => 'put',
                        'params' => [
                            PersonUrlBuilder::resetPin(self::USER_ID),
                            null
                        ],
                        'result' => new \Exception('/', 10),
                    ],
                    [
                        'class'  => 'mockUserAdminSessionSrv',
                        'method' => 'isUserAuthenticated',
                        'params' => [],
                        'result' => true,
                    ],
                ],
                'expect' => [
                    'result' => [
                        'fullName' => 'Mr foo bar baz',
                        'config'   => self::$CONFIG,
                        'userId'   => self::USER_ID,
                    ],
                ],
            ],
            //  --  securitySettings: fail --
            [
                'method' => 'post',
                'action' => 'securitySettings',
                'params' => [
                    'post' => [],
                ],
                'mocks'  => [
                    [
                        'class'  => 'mockRestClient',
                        'method' => 'put',
                        'params' => [
                            PersonUrlBuilder::resetPin(self::USER_ID),
                            null
                        ],
                        'result' => new GeneralRestException('/', 'post', [], 10, 'Person not found'),
                    ],
                    [
                        'class'  => 'mockUserAdminSessionSrv',
                        'method' => 'isUserAuthenticated',
                        'params' => [],
                        'result' => true,
                    ],
                ],
                'expect' => [
                    'result' => [
                        'fullName' => 'Mr foo bar baz',
                        'config'   => self::$CONFIG,
                        'userId'   => self::USER_ID,
                    ],
                ],
            ],
        ];
    }

    private function getPersonalDetailsData()
    {
        return [
            'id'                   => 1,
            'firstName'            => 'foo',
            'middleName'           => 'bar',
            'surname'              => 'baz',
            'username'             => 'tester1',
            'dateOfBirth'          => '1979-12-20',
            'title'                => 'Mr',
            'gender'               => 'male',
            'addressLine1'         => 'foo',
            'addressLine2'         => 'foo',
            'addressLine3'         => 'foo',
            'town'                 => 'foo',
            'postcode'             => 'AA11 1AA',
            'email'                => 'foo',
            'emailConfirmation'    => null,
            'phone'                => 1234,
            'drivingLicenceNumber' => 'foo',
            'drivingLicenceRegion' => 'bar',
            'positions'            => [],
            'roles'                => [
                "system" => [ "roles" => [] ],
                "organisations" => [],
                "sites" => []
            ],
        ];
    }

    private function getDashboarhData()
    {
        return [
            'hero'                   => null,
            'permissions'            => null,
            'specialNotice'          => [
                'daysLeftToView' => null,
                'unreadCount'    => null,
                'overdueCount'   => null,
            ],
            'notifications'          => [],
            'inProgressTestNumber'   => null,
            'inProgressTestTypeCode' => null,
            'authorisedExaminers'    => [],
        ];
    }

    public function testGetAuthenticatedDataResult()
    {
        $authResult = 'authResult';
        $this->mockMethod(
            $this->mockPersonalDetailsSrv, 'getPersonalAuthorisationForMotTesting', null, $authResult, self::USER_ID
        );

        $actual = $this->getResultForAction('profile');

        $this->assertArrayHasKey('personalDetails', $actual);
        $this->assertArrayHasKey('isAllowEdit', $actual);
        $this->assertArrayHasKey('motAuthorisations', $actual);
        $this->assertArrayHasKey('isViewingOwnProfile', $actual);
        $this->assertArrayHasKey('countries', $actual);

        $this->assertEquals(new PersonalDetails($this->getPersonalDetailsData()), $actual['personalDetails']);
        //Removed assert due to lack of mocks.
        //$this->assertEquals(true, $actual['isAllowEdit']);
        $this->assertEquals($authResult, $actual['motAuthorisations']);
        $this->assertEquals(true, $actual['isViewingOwnProfile']);
        $this->assertEquals(['uk' => 'ukLong'], $actual['countries']);
    }
}
