<?php
namespace OrganisationTest\Controller;

use Core\Service\MotFrontendAuthorisationServiceInterface;
use CoreTest\Controller\AbstractFrontendControllerTestCase;
use DvsaClient\Mapper\OrganisationMapper;
use DvsaClient\MapperFactory;
use DvsaClient\ViewModel\EmailFormModel;
use DvsaClient\ViewModel\PhoneFormModel;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Constants\FeatureToggle;
use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Organisation\AuthorisedExaminerAuthorisationDto;
use DvsaCommon\Dto\Organisation\OrganisationContactDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommon\Exception\UnauthorisedException;
use DvsaCommon\HttpRestJson\Exception\RestApplicationException;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilderWeb;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\TestUtils\TestCasePermissionTrait;
use DvsaCommonTest\TestUtils\XMock;
use DvsaFeature\Exception\FeatureNotAvailableException;
use DvsaFeature\FeatureToggles;
use Organisation\Controller\AuthorisedExaminerController;
use Organisation\Form\AeContactDetailsForm;
use Organisation\Form\AeCreateForm;
use Organisation\ViewModel\AuthorisedExaminer\AeFormViewModel;
use PHPUnit_Framework_MockObject_MockObject as MockObj;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * Class AuthorisedExaminerControllerTest
 *
 * @package Organisation\Test
 */
class AuthorisedExaminerControllerTest extends AbstractFrontendControllerTestCase
{
    use TestCasePermissionTrait;

    const AE_ID = 1;
    const PERSON_ID = 1;

    const SESSION_KEY = 'test_sessKey';

    /**
     * @var MotFrontendAuthorisationServiceInterface|MockObj $mockAuth
     */
    private $mockAuth;
    /**
     * @var MapperFactory|MockObj $mapper
     */
    private $mockMapperFactory;
    /**
     * @var MotIdentityProviderInterface|MockObj $mockIdentity
     */
    private $mockIdentity;
    /**
     * @var Container
     */
    private $mockSession;
    /**
     * @var OrganisationMapper|MockObj
     */
    private $mockOrgMapper;
    /**
     * @var FeatureToggles|MockObj
     */
    private $mockFeatureToggle;

    public function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $serviceManager->setAllowOverride(true);
        $this->setServiceManager($serviceManager);

        $this->mockAuth = XMock::of(MotFrontendAuthorisationServiceInterface::class);
        $this->mockIdentity = XMock::of(MotIdentityProviderInterface::class);
        $this->mockMapperFactory = $this->getMapperFactory();
        $this->mockSession = XMock::of(Container::class);

        $this->setController(
            new AuthorisedExaminerController(
                $this->mockAuth, $this->mockMapperFactory, $this->mockIdentity, $this->mockSession
            )
        );

        $this->getController()->setServiceLocator($this->serviceManager);

        parent::setUp();

        $this->mockFeatureToggle = XMock::of(FeatureToggles::class, ['isEnabled']);
        $serviceManager->setService('Feature\FeatureToggles', $this->mockFeatureToggle);
    }

    /**
     * @dataProvider dataProviderTestActionsResult
     */
    public function testActionsResult($method, $action, $params, $mocks, $expect)
    {
        $result = null;

        //  logical block :: mock
        //  enable any feature
        $this->mockMethod($this->mockFeatureToggle, 'isEnabled', $this->any(), true);

        //  mocking methods
        if ($mocks !== null) {
            foreach ($mocks as $mock) {
                $invocation = (isset($mock['call']) ? $mock['call'] : $this->once());
                $mockParams = (isset($mock['params']) ? $mock['params'] : null);

                $this->mockMethod($this->{$mock['class']}, $mock['method'], $invocation, $mock['result'], $mockParams);
            }
        }

        //  check :: set expected exception
        if (!empty($expect['exception'])) {
            $exception = $expect['exception'];
            $this->setExpectedException($exception['class'], $exception['message']);
        }

        // logical block :: call
        $result = $this->getResultForAction2(
            $method,
            $action,
            ArrayUtils::tryGet($params, 'route'),
            ArrayUtils::tryGet($params, 'get'),
            ArrayUtils::tryGet($params, 'post')
        );

        // logical block :: check
        if (!empty($expect['viewModel'])) {
            $this->assertInstanceOf(ViewModel::class, $result);
            $this->assertResponseStatus(self::HTTP_OK_CODE);
        }

        if (!empty($expect['viewForm']) || !empty($expect['errors'])) {
            /** @var AeFormViewModel $module */
            /** @var AeCreateForm $form */
            $model = $result->getVariable('model');
            $form = $model->getForm();

            if (!empty($expect['viewForm'])) {
                $expectForm = $expect['viewForm'];
                $expectFormObj = $expectForm['obj'];

                $this->assertInstanceOf(get_class($expectFormObj), $form);

                if ($expectForm['isSame']) {
                    $this->assertSame($expectFormObj, $form);
                } else {
                    $this->assertNotSame($expectFormObj, $form);
                }
            }

            if (!empty($expect['errors'])) {
                foreach ($expect['errors'] as $field => $error) {
                    $this->assertEquals($error, $form->getError($field));
                }
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

    public function dataProviderTestActionsResult()
    {
        /** @var AeCreateForm|MockObj $formAeCreate */
        $formAeCreate = XMock::of(AeCreateForm::class);
        $this->mockMethod($formAeCreate, 'toDto', null, new OrganisationDto());

        return [
            // has access
            [
                'method' => 'get',
                'action' => 'create',
                'params' => [],
                'mocks'  => [],
                'expect' => [
                    'viewModel' => true,
                ],
            ],
            //  form not in session, create a new form;
            [
                'method' => 'get',
                'action' => 'create',
                'params' => [],
                'mocks'  => [],
                'expect' => [
                    'viewModel' => true,
                    'viewForm'  => [
                        'obj'    => new AeCreateForm(),
                        'isSame' => false,
                    ],
                ],
            ],
            //  get form from session; form is valid; redirect to confirmation
            [
                'method' => 'post',
                'action' => 'create',
                'params' => [
                    'get' => [
                        AuthorisedExaminerController::SESSION_KEY => self::SESSION_KEY,
                    ],
                ],
                'mocks'  => [
                    [
                        'class'  => 'mockSession',
                        'method' => 'offsetGet',
                        'params' => [self::SESSION_KEY],
                        'result' => $this->mockMethod($this->cloneObj($formAeCreate), 'isValid', $this->once(), true),
                    ],
                    [
                        'class'  => 'mockOrgMapper',
                        'method' => 'validate',
                        'params' => [$formAeCreate->toDto()],
                        'result' => [],
                    ],
                ],
                'expect' => [
                    'url' => AuthorisedExaminerUrlBuilderWeb::createConfirm()
                        ->queryParam(AuthorisedExaminerController::SESSION_KEY, self::SESSION_KEY),
                ],
            ],

            //  logical block :: create confirmation action
            //  form not in session; redirect ot create form
            [
                'method' => 'get',
                'action' => 'confirmation',
                'params' => [
                    'get' => [
                        AuthorisedExaminerController::SESSION_KEY => self::SESSION_KEY,
                    ],
                ],
                'mocks'  => [],
                'expect' => [
                    'url' => AuthorisedExaminerUrlBuilderWeb::create(),
                ],
            ],
            // form in session; show confirm page
            [
                'method' => 'get',
                'action' => 'confirmation',
                'params' => [
                    'get' => [
                        AuthorisedExaminerController::SESSION_KEY => self::SESSION_KEY,
                    ],
                ],
                'mocks'  => [
                    [
                        'class'  => 'mockSession',
                        'method' => 'offsetGet',
                        'params' => [self::SESSION_KEY],
                        'result' => $formAeCreate,
                    ],
                ],
                'expect' => [
                    'viewModel' => true,
                    'viewForm'  => [
                        'obj'    => $formAeCreate,
                        'isSame' => true,
                    ],
                ],
            ],
            // form in session; post successful; redirect to AE view
            [
                'method' => 'post',
                'action' => 'confirmation',
                'params' => [
                    'get' => [
                        AuthorisedExaminerController::SESSION_KEY => self::SESSION_KEY,
                    ],
                ],
                'mocks'  => [
                    [
                        'class'  => 'mockSession',
                        'method' => 'offsetGet',
                        'params' => [self::SESSION_KEY],
                        'result' => $formAeCreate,
                    ],
                    [
                        'class'  => 'mockSession',
                        'method' => 'offsetUnset',
                        'params' => [self::SESSION_KEY],
                        'result' => null,
                    ],
                    [
                        'class'  => 'mockOrgMapper',
                        'method' => 'create',
                        'params' => [$formAeCreate->toDto()],
                        'result' => ['id' => self::AE_ID],
                    ],
                ],
                'expect' => [
                    'url' => AuthorisedExaminerUrlBuilderWeb::of(self::AE_ID),
                ],
            ],
            // form in session; error in during post; show confirm page and flash error
            [
                'method' => 'post',
                'action' => 'confirmation',
                'params' => [
                    'get' => [
                        AuthorisedExaminerController::SESSION_KEY => self::SESSION_KEY,
                    ],
                ],
                'mocks'  => [
                    [
                        'class'  => 'mockSession',
                        'method' => 'offsetGet',
                        'params' => [self::SESSION_KEY],
                        'result' => $formAeCreate,
                    ],
                    [
                        'class'  => 'mockSession',
                        'method' => 'offsetUnset',
                        'params' => [self::SESSION_KEY],
                        'result' => null,
                        'call'   => $this->never(),
                    ],
                    [
                        'class'  => 'mockOrgMapper',
                        'method' => 'create',
                        'params' => [$formAeCreate->toDto()],
                        'result' => new RestApplicationException(
                            '/', 'post', [], 10, [['displayMessage' => 'something wrong']]
                        ),
                    ],
                ],
                'expect' => [
                    'viewModel'  => true,
                    'viewForm'   => [
                        'obj'    => $formAeCreate,
                        'isSame' => true,
                    ],
                    'flashError' => 'something wrong',
                ],
            ],

            //  --  edit contact details: access action  --
            [
                'method' => 'get',
                'action' => 'edit',
                'params' => [
                    'route' => [
                        'id' => self::AE_ID
                    ]
                ],
                'mocks'  => [
                    [
                        'class'  => 'mockOrgMapper',
                        'method' => 'getAuthorisedExaminer',
                        'params' => [self::AE_ID],
                        'result' => $this->getOrganisation(),
                    ],
                ],
                'expect' => [
                    'viewModel' => true,
                ],
            ],
            //  --  edit contact details: post action --
            [
                'method' => 'post',
                'action' => 'edit',
                'params' => [
                    'post'  => [
                        OrganisationContactTypeCode::CORRESPONDENCE       => [
                            EmailFormModel::FIELD_IS_NOT_SUPPLY => 1,
                            PhoneFormModel::FIELD_NUMBER        => '0123453454',
                        ],
                        AeContactDetailsForm::FIELD_IS_CORR_ADDR_THE_SAME => 1,
                    ],
                    'route' => [
                        'id' => self::AE_ID
                    ],
                ],
                'mocks'  => [
                    [
                        'class'  => 'mockOrgMapper',
                        'method' => 'getAuthorisedExaminer',
                        'params' => [self::AE_ID],
                        'result' => $this->getOrganisation(),
                    ],
                    [
                        'class'  => 'mockOrgMapper',
                        'method' => 'update',
                        'params' => [],
                        'result' => ['id' => self::AE_ID],
                    ],
                ],
                'expect' => [
                    'url' => AuthorisedExaminerUrlBuilderWeb::of(self::AE_ID),
                ],
            ],
            //  --  edit contact details: post action error --
            [
                'method' => 'post',
                'action' => 'edit',
                'params' => [
                    'post'  => [
                        OrganisationContactTypeCode::CORRESPONDENCE       => [
                            EmailFormModel::FIELD_IS_NOT_SUPPLY => 1,
                            PhoneFormModel::FIELD_NUMBER        => '0123453454',
                        ],
                        AeContactDetailsForm::FIELD_IS_CORR_ADDR_THE_SAME => 1,
                    ],
                    'route' => [
                        'id' => self::AE_ID
                    ],
                ],
                'mocks'  => [
                    [
                        'class'  => 'mockOrgMapper',
                        'method' => 'getAuthorisedExaminer',
                        'params' => [self::AE_ID],
                        'result' => $this->getOrganisation(),
                    ],
                    [
                        'class'  => 'mockOrgMapper',
                        'method' => 'update',
                        'params' => [],
                        'result' => new RestApplicationException(
                            '/', 'post', [], 10, [['displayMessage' => 'something wrong']]
                        ),
                    ],
                ],
                'expect' => [
                    'viewModel'  => true,
                    'flashError' => 'something wrong',
                ],
            ],

            //  --  index: access action from AE search --
            [
                'method' => 'get',
                'action' => 'index',
                'params' => [
                    'route' => [
                        'id' => self::AE_ID
                    ]
                ],
                'mocks'  => [
                    [
                        'class'  => 'mockOrgMapper',
                        'method' => 'getAuthorisedExaminer',
                        'params' => [self::AE_ID],
                        'result' => $this->getOrganisation(),
                    ],
                    [
                        'class'  => 'mockAuth',
                        'call'   => $this->at(4),
                        'method' => 'isGranted',
                        'params' => [PermissionInSystem::AUTHORISED_EXAMINER_LIST],
                        'result' => true,
                    ],
                ],
                'expect' => [
                    'viewModel' => true,
                ],
            ],
            //  --  index: access action from user search --
            [
                'method' => 'get',
                'action' => 'index',
                'params' => [
                    'route' => [
                        'id' => self::AE_ID
                    ]
                ],
                'mocks'  => [
                    [
                        'class'  => 'mockOrgMapper',
                        'method' => 'getAuthorisedExaminer',
                        'params' => [self::AE_ID],
                        'result' => $this->getOrganisation(),
                    ],
                    [
                        'class'  => 'mockAuth',
                        'call'   => $this->at(6),
                        'method' => 'isGranted',
                        'params' => [PermissionInSystem::USER_SEARCH],
                        'result' => true,
                    ],
                ],
                'expect' => [
                    'viewModel' => true,
                ],
            ],
            //  --  index: access action  --
            [
                'method' => 'get',
                'action' => 'index',
                'params' => [
                    'route' => [
                        'id' => self::AE_ID
                    ]
                ],
                'mocks'  => [
                    [
                        'class'  => 'mockOrgMapper',
                        'method' => 'getAuthorisedExaminer',
                        'params' => [self::AE_ID],
                        'result' => $this->getOrganisation(),
                    ],
                ],
                'expect' => [
                    'viewModel' => true,
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderTestNoAccessFeatureDisabled
     */
    public function testNoAccessFeatureDisabled($action, $feature)
    {
        //  logical block :: mock
        //  turn off features
        $this->mockMethod($this->mockFeatureToggle, 'isEnabled', $this->once(), false, [$feature]);

        //  set expected exception
        $this->setExpectedException(
            FeatureNotAvailableException::class,
            'Feature "' . $feature . '" is either disabled or not available in the current application configuration.'
        );

        $this->getResultForAction($action);
    }

    public function dataProviderTestNoAccessFeatureDisabled()
    {
        return [
            [
                'action'  => 'create',
                'feature' => FeatureToggle::AO1_AE_CREATE,
            ],
            ['confirmation', FeatureToggle::AO1_AE_CREATE],
        ];
    }

    /**
     * @dataProvider dataProviderTestNoAccessNoPerm
     */
    public function testHaveNoAccessNoPerm($action, $params)
    {
        $result = null;

        //  logical block :: mock
        //  turn on features
        $this->mockMethod($this->mockFeatureToggle, 'isEnabled', $this->any(), true);

        //  revoke all permissions
        $this->mockAssertGranted($this->mockAuth, []);
        $this->mockIsGranted($this->mockAuth, []);
        $this->mockAssertGrantedAtOrganisation($this->mockAuth, [], self::AE_ID);
        $this->mockIsGrantedAtOrganisation($this->mockAuth, [], self::AE_ID);

        //  set expected exception
        $this->setExpectedException(UnauthorisedException::class, 'You not have permissions');

        //  logical block :: call
        $this->getResultForAction($action, $params);
    }

    public function dataProviderTestNoAccessNoPerm()
    {
        return [
            [
                'action' => 'create',
                'params' => [],
            ],
            ['confirmation', []],
            ['edit', ['id' => self::AE_ID]],
            ['index', ['id' => self::AE_ID]],
        ];
    }

    private function getMapperFactory()
    {
        $mapperFactory = XMock::of(MapperFactory::class);

        $this->mockOrgMapper = XMock::of(OrganisationMapper::class, []);
        $this->mockOrgMapper->expects($this->any())
            ->method('getAllAreaOffices')
            ->willReturn($this-> fakedAreaOfficeList());

        $map = [
            [MapperFactory::ORGANISATION, $this->mockOrgMapper],
        ];

        $mapperFactory->expects($this->any())
            ->method('__get')
            ->will($this->returnValueMap($map));

        return $mapperFactory;
    }

    protected function fakedAreaOfficeList()
    {
        return [
            [
                "id" => "3000",
                "name" => "Area Office 01",
                "siteNumber" => "01FOO",
                "areaOfficeNumber" => "01"
            ],
            [
                "id" => "3001",
                "name" => "Area Office 02",
                "siteNumber" => "02BAR",
                "areaOfficeNumber" => "02"
            ]
        ];
    }

    private function getOrganisation()
    {
        $orgDto = new OrganisationDto();
        $orgDto->setId(self::AE_ID);

        $orgContactDto = new OrganisationContactDto();
        $orgContactDto->setType(OrganisationContactTypeCode::REGISTERED_COMPANY);

        $orgAddressDto = new AddressDto();
        $orgAddressDto->setAddressLine1('test')
            ->setAddressLine2('test')
            ->setAddressLine3('test')
            ->setPostcode('test')
            ->setTown('test');

        $orgContactDto->setAddress($orgAddressDto);

        $orgContactDtoCor = clone $orgContactDto;
        $orgContactDtoCor->setType(OrganisationContactTypeCode::CORRESPONDENCE);

        $orgDto->setContacts([$orgContactDto, $orgContactDtoCor]);
        $orgDto->setAuthorisedExaminerAuthorisation(new AuthorisedExaminerAuthorisationDto());

        return $orgDto;
    }

    /**
     * @return $obj
     */
    private function cloneObj($obj)
    {
        return clone $obj;
    }
}
