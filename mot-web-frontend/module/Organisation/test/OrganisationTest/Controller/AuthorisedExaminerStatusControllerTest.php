<?php
namespace OrganisationTest\Controller;

use Core\Service\MotFrontendAuthorisationServiceInterface;
use CoreTest\Controller\AbstractFrontendControllerTestCase;
use DvsaClient\Mapper\OrganisationMapper;
use DvsaClient\MapperFactory;
use DvsaCommon\Auth\MotIdentityProviderInterface;
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
use Organisation\Controller\AuthorisedExaminerStatusController;
use Organisation\Form\AeStatusForm;
use Organisation\ViewModel\AuthorisedExaminer\AeFormViewModel;
use PHPUnit_Framework_MockObject_MockObject as MockObj;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * Class AuthorisedExaminerStatusControllerTest
 *
 * @package Organisation\Test
 */
class AuthorisedExaminerStatusControllerTest extends AbstractFrontendControllerTestCase
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
            new AuthorisedExaminerStatusController(
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

        // logical block :: call
        $result = $this->getResultForAction2(
            $method,
            $action,
            ArrayUtils::tryGet($params, 'route'),
            ArrayUtils::tryGet($params, 'get')
        );

        // logical block :: check
        if (!empty($expect['viewModel'])) {
            $this->assertInstanceOf(ViewModel::class, $result);
            $this->assertResponseStatus(self::HTTP_OK_CODE);
        }

        if (!empty($expect['viewForm']) || !empty($expect['errors'])) {
            /** @var AeFormViewModel $model */
            /** @var AeStatusForm $form */
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
        /** @var AeStatusForm|MockObj $formAeStatus */
        $formAeStatus = XMock::of(AeStatusForm::class);
        $this->mockMethod($formAeStatus, 'toDto', null, new OrganisationDto());

        return [
            // has access
            [
                'method' => 'get',
                'action' => 'index',
                'params' => [
                    'route' => [
                        'id' => self::AE_ID
                    ],
                ],
                'mocks'  => [],
                'expect' => [
                    'viewModel' => true,
                ],
            ],
            //  form not in session, create a new form;
            [
                'method' => 'get',
                'action' => 'index',
                'params' => [
                    'route' => [
                        'id' => self::AE_ID
                    ],
                ],
                'mocks'  => [],
                'expect' => [
                    'viewModel' => true,
                    'viewForm'  => [
                        'obj'    => new AeStatusForm(),
                        'isSame' => false,
                    ],
                ],
            ],
            //  get form from session; form is valid; redirect to confirmation
            [
                'method' => 'post',
                'action' => 'index',
                'params' => [
                    'route' => [
                        'id' => self::AE_ID
                    ],
                    'get' => [
                        AuthorisedExaminerStatusController::SESSION_KEY => self::SESSION_KEY,
                    ],
                ],
                'mocks'  => [
                    [
                        'class'  => 'mockSession',
                        'method' => 'offsetGet',
                        'params' => [self::SESSION_KEY],
                        'result' => $formAeStatus,
                    ],
                    [
                        'class'  => 'mockOrgMapper',
                        'method' => 'validateStatus',
                        'params' => [$formAeStatus->toDto()],
                        'result' => ['id' => self::AE_ID],
                    ],
                ],
                'expect' => [
                    'url' => AuthorisedExaminerUrlBuilderWeb::aeEditStatusConfirm(self::AE_ID)
                        ->queryParam(AuthorisedExaminerStatusController::SESSION_KEY, self::SESSION_KEY),
                ],
            ],

            //  logical block :: create confirmation action
            //  form not in session; redirect ot create form
            [
                'method' => 'get',
                'action' => 'confirmation',
                'params' => [
                    'route' => [
                        'id' => self::AE_ID
                    ],
                    'get' => [
                        AuthorisedExaminerStatusController::SESSION_KEY => self::SESSION_KEY,
                    ],
                ],
                'mocks'  => [],
                'expect' => [
                    'url' => AuthorisedExaminerUrlBuilderWeb::aeEditStatus(self::AE_ID),
                ],
            ],
            // form in session; show confirm page
            [
                'method' => 'get',
                'action' => 'confirmation',
                'params' => [
                    'route' => [
                        'id' => self::AE_ID
                    ],
                    'get' => [
                        AuthorisedExaminerStatusController::SESSION_KEY => self::SESSION_KEY,
                    ],
                ],
                'mocks'  => [
                    [
                        'class'  => 'mockSession',
                        'method' => 'offsetGet',
                        'params' => [self::SESSION_KEY],
                        'result' => $formAeStatus,
                    ],
                ],
                'expect' => [
                    'viewModel' => true,
                    'viewForm'  => [
                        'obj'    => $formAeStatus,
                        'isSame' => true,
                    ],
                ],
            ],
            // form in session; post successful; redirect to AE view
            [
                'method' => 'post',
                'action' => 'confirmation',
                'params' => [
                    'route' => [
                        'id' => self::AE_ID
                    ],
                    'get' => [
                        AuthorisedExaminerStatusController::SESSION_KEY => self::SESSION_KEY,
                    ],
                ],
                'mocks'  => [
                    [
                        'class'  => 'mockSession',
                        'method' => 'offsetGet',
                        'params' => [self::SESSION_KEY],
                        'result' => $formAeStatus,
                    ],
                    [
                        'class'  => 'mockSession',
                        'method' => 'offsetUnset',
                        'params' => [self::SESSION_KEY],
                        'result' => null,
                    ],
                    [
                        'class'  => 'mockOrgMapper',
                        'method' => 'status',
                        'params' => [$formAeStatus->toDto()],
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
                    'route' => [
                        'id' => self::AE_ID
                    ],
                    'get' => [
                        AuthorisedExaminerStatusController::SESSION_KEY => self::SESSION_KEY,
                    ],
                ],
                'mocks'  => [
                    [
                        'class'  => 'mockSession',
                        'method' => 'offsetGet',
                        'params' => [self::SESSION_KEY],
                        'result' => $formAeStatus,
                    ],
                    [
                        'class'  => 'mockOrgMapper',
                        'method' => 'status',
                        'params' => [$formAeStatus->toDto()],
                        'result' => new RestApplicationException(
                            '/', 'post', [], 10, [['displayMessage' => 'something wrong']]
                        ),
                    ],
                ],
                'expect' => [
                    'viewModel'  => true,
                    'viewForm'   => [
                        'obj'    => $formAeStatus,
                        'isSame' => true,
                    ],
                    'flashError' => 'something wrong',
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
                'action'  => 'index',
                'feature' => FeatureToggle::AO1_AE_EDIT_STATUS,
            ],
            ['confirmation', FeatureToggle::AO1_AE_EDIT_STATUS],
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
                'action' => 'index',
                'params' => [],
            ],
            ['confirmation', []],
        ];
    }

    private function getMapperFactory()
    {
        $mapperFactory = XMock::of(MapperFactory::class);

        $this->mockOrgMapper = XMock::of(OrganisationMapper::class);

        $this->mockOrgMapper->expects($this->any())
            ->method('getAuthorisedExaminer')
            ->with(self::AE_ID)
            ->willReturn($this->getOrganisation());

        $map = [
            [MapperFactory::ORGANISATION, $this->mockOrgMapper],
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

        $orgDto->setContacts([$orgContactDto]);
        $orgDto->setAuthorisedExaminerAuthorisation(new AuthorisedExaminerAuthorisationDto());

        return $orgDto;
    }
}
