<?php
namespace OrganisationTest\Controller;

use Core\Service\MotFrontendAuthorisationServiceInterface;
use DvsaClient\Mapper\OrganisationMapper;
use DvsaClient\Mapper\VehicleTestingStationMapper;
use DvsaClient\MapperFactory;
use DvsaClient\ViewModel\AddressFormModel;
use DvsaClient\ViewModel\EmailFormModel;
use DvsaClient\ViewModel\PhoneFormModel;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Organisation\AuthorisedExaminerAuthorisationDto;
use DvsaCommon\Dto\Organisation\OrganisationContactDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommon\HttpRestJson\Exception\RestApplicationException;
use DvsaCommon\HttpRestJson\Exception\ValidationException;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilderWeb;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonTest\Bootstrap;
use CoreTest\Controller\AbstractFrontendControllerTestCase;
use DvsaCommonTest\TestUtils\XMock;
use DvsaFeature\FeatureToggles;
use Organisation\Controller\AuthorisedExaminerController;
use Organisation\Form\AeContactDetailsForm;
use Organisation\Form\AeCreateForm;
use Zend\View\Model\ViewModel;
use \PHPUnit_Framework_MockObject_MockObject as MockObj;

/**
 * Class AuthorisedExaminerControllerTest
 *
 * @package Organisation\Test
 */
class AuthorisedExaminerControllerTest extends AbstractFrontendControllerTestCase
{
    const AE_ID = 1;
    const PERSON_ID = 1;

    /** @var MotFrontendAuthorisationServiceInterface|MockObj $auth */
    private $auth;
    /** @var MapperFactory|MockObj $mapper */
    private $mapperFactory;
    /** @var MotIdentityProviderInterface|MockObj $identity */
    private $identity;
    private $mockOrgMapper;
    private $mockSiteMapper;

    protected $serviceManager;

    public function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
        $this->serviceManager->setAllowOverride(true);
        $this->setServiceManager($this->serviceManager);

        $this->auth = XMock::of(MotFrontendAuthorisationServiceInterface::class);
        $this->identity = XMock::of(MotIdentityProviderInterface::class);
        $this->mapperFactory = $this->getMapperFactory();

        $this->setController(new AuthorisedExaminerController($this->auth, $this->mapperFactory, $this->identity));

        $this->getController()->setServiceLocator($this->serviceManager);

        parent::setUp();

        $featureToggle = XMock::of(FeatureToggles::class);
        $this->serviceManager->setService('Feature\FeatureToggles', $featureToggle);
        $featureToggle->expects($this->any())
            ->method('isEnabled')
            ->willReturn('true');
    }

    /**
     * @dataProvider dataProviderTestActionsResultAndAccess
     */
    public function testActionsResultAndAccess($method, $action, $params, $mocks, $expect)
    {
        $result = null;

        if ($mocks !== null) {
            foreach ($mocks as $mock) {
                $invocation = (isset($mock['call']) ? $mock['call'] : $this->once());
                $mockParams = (isset($mock['params']) ? $mock['params'] : null);

                $this->mockMethod($this->{$mock['class']}, $mock['method'], $invocation, $mock['result'], $mockParams);
            }
        }

        //  --  set expected exception  --
        if (!empty($expect['exception'])) {
            $exception = $expect['exception'];
            $this->setExpectedException($exception['class'], $exception['message']);
        }

        $result = $this->getResultForAction2(
            $method,
            $action,
            ArrayUtils::tryGet($params, 'route'),
            ArrayUtils::tryGet($params, 'get'),
            ArrayUtils::tryGet($params, 'post')
        );

        //  --  check   --
        if (!empty($expect['viewModel'])) {
            $this->assertInstanceOf(ViewModel::class, $result);
            $this->assertResponseStatus(self::HTTP_OK_CODE);
        }

        if (!empty($expect['errors'])) {
            $this->assertInstanceOf(ViewModel::class, $result);

            $form = $result->getVariable('form');

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
        $aeCreatePostValid = [
            AeCreateForm::FIELD_NAME                     => 'testOrgName',
            AeCreateForm::FIELD_TRADING_AS               => 'testTradAs',
            AeCreateForm::FIELD_COMPANY_TYPE             => 'testCompType',
            AeCreateForm::FIELD_REG_NR                   => 'testRegNr',
            'REGC'                                       => [
                AddressFormModel::FIELD_LINE1       => 'testAddressLine',
                AddressFormModel::FIELD_TOWN        => 'testTown',
                AddressFormModel::FIELD_POSTCODE    => 'testPostcode',
                PhoneFormModel::FIELD_NUMBER        => 'testPhone',
                EmailFormModel::FIELD_IS_NOT_SUPPLY => 1,
            ],
            AeCreateForm::FIELD_AO_NR                    => '1',
            AeCreateForm::FIELD_IS_CORR_DETAILS_THE_SAME => 1,
        ];

        return [
            //  --  create: access action  --
            [
                'method' => 'get',
                'action' => 'create',
                'params' => [],
                'mocks'  => [],
                'expect' => [
                    'viewModel' => true,
                ],
            ],
            //  --  create: post action:: success  --
            [
                'method'   => 'post',
                'action'   => 'create',
                'params' => [
                    'post' => $aeCreatePostValid,
                ],
                'mocks'    => [
                    [
                        'class'  => 'mockOrgMapper',
                        'method' => 'create',
                        'result' => ['id' => self::AE_ID],
                    ],
                ],
                'expect'   => [
                    'url' => AuthorisedExaminerUrlBuilderWeb::of(self::AE_ID),
                ],
            ],

            //  --  create: post action:: error form validation  --
            /*
            //  #TODO  uncomment when validation will be complete
            [
                'method' => 'post',
                'action' => 'create',
                'params' => [
                    'post' => $aeCreatePostValid,
                ],
                'mocks'  => [],
                'expect' => [
                    'url'    => AuthorisedExaminerUrlBuilderWeb::of(self::AE_ID),
                    'errors' => [
                        AeCreateForm::FIELD_NAME => AeCreateForm::ERR_NAME_REQUIRE,
                    ],
                ],
            ],
            */

            //  --  create: post action errors --
            [
                'method'   => 'post',
                'action'   => 'create',
                'params' => [
                    'post' => $aeCreatePostValid,
                ],
                'mocks'    => [
                    [
                        'class'  => 'mockOrgMapper',
                        'method' => 'create',
                        'params' => [],
                        'result' => new ValidationException(
                            '/', 'post', [], 10, [['displayMessage' => 'something wrong']]
                        ),
                    ],
                ],
                'expect'   => [
                    'viewModel'  => true,
                    'flashError' => 'something wrong',
                ],
            ],

            //  --  edit contact details: access action  --
            [
                'method'   => 'get',
                'action'   => 'edit',
                'params' => [
                    'route' => [
                        'id' => self::AE_ID
                    ]
                ],
                'mocks'    => [
                    [
                        'class'  => 'mockOrgMapper',
                        'method' => 'getAuthorisedExaminer',
                        'params' => [self::AE_ID],
                        'result' => $this->getOrganisation(),
                    ],
                ],
                'expect'   => [
                    'viewModel' => true,
                ],
            ],
            //  --  edit contact details: post action --
            [
                'method'   => 'post',
                'action'   => 'edit',
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
                'mocks'    => [
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
                'expect'   => [
                    'url' => AuthorisedExaminerUrlBuilderWeb::of(self::AE_ID),
                ],
            ],
            //  --  edit contact details: post action error --
            [
                'method'   => 'post',
                'action'   => 'edit',
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
                'mocks'    => [
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
                'expect'   => [
                    'viewModel' => true,
                    'flashError' => 'something wrong',
                ],
            ],

            //  --  index: access action from AE search --
            [
                'method'   => 'get',
                'action'   => 'index',
                'params' => [
                    'route' => [
                        'id' => self::AE_ID
                    ]
                ],
                'mocks'    => [
                    [
                        'class'  => 'mockOrgMapper',
                        'method' => 'getAuthorisedExaminer',
                        'params' => [self::AE_ID],
                        'result' => $this->getOrganisation(),
                    ],
                    [
                        'class'  => 'auth',
                        'call'   => $this->at(4),
                        'method' => 'isGranted',
                        'params' => [PermissionInSystem::AUTHORISED_EXAMINER_LIST],
                        'result' => true,
                    ],
                ],
                'expect'   => [
                    'viewModel' => true,
                ],
            ],
            //  --  index: access action from user search --
            [
                'method'   => 'get',
                'action'   => 'index',
                'params' => [
                    'route' => [
                        'id' => self::AE_ID
                    ]
                ],
                'mocks'    => [
                    [
                        'class'  => 'mockOrgMapper',
                        'method' => 'getAuthorisedExaminer',
                        'params' => [self::AE_ID],
                        'result' => $this->getOrganisation(),
                    ],
                    [
                        'class'  => 'auth',
                        'call'   => $this->at(6),
                        'method' => 'isGranted',
                        'params' => [PermissionInSystem::USER_SEARCH],
                        'result' => true,
                    ],
                ],
                'expect'   => [
                    'viewModel' => true,
                ],
            ],
            //  --  index: access action  --
            [
                'method'   => 'get',
                'action'   => 'index',
                'params' => [
                    'route' => [
                        'id' => self::AE_ID
                    ]
                ],
                'mocks'    => [
                    [
                        'class'  => 'mockOrgMapper',
                        'method' => 'getAuthorisedExaminer',
                        'params' => [self::AE_ID],
                        'result' => $this->getOrganisation(),
                    ],
                ],
                'expect'   => [
                    'viewModel' => true,
                ],
            ],
        ];
    }

    private function getMapperFactory()
    {
        //  ----
        $mapperFactory = XMock::of(MapperFactory::class);

        $this->mockOrgMapper = XMock::of(OrganisationMapper::class);
        $this->mockSiteMapper = XMock::of(VehicleTestingStationMapper::class);

        $map = [
            [MapperFactory::ORGANISATION, $this->mockOrgMapper],
            [MapperFactory::VEHICLE_TESTING_STATION, $this->mockSiteMapper],
        ];

        $mapperFactory->expects($this->any())
            ->method('__get')
            ->will($this->returnValueMap($map));

        return $mapperFactory;
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

        $orgDto->setContacts([ $orgContactDto, $orgContactDtoCor ]);
        $orgDto->setAuthorisedExaminerAuthorisation(new AuthorisedExaminerAuthorisationDto());

        return $orgDto;
    }
}
