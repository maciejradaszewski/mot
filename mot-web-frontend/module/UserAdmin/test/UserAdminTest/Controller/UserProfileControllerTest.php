<?php

namespace UserAdminTest\Controller;

use Application\Helper\PrgHelper;
use CoreTest\Controller\AbstractFrontendControllerTestCase;
use DvsaClient\Entity\TesterAuthorisation;
use DvsaClient\Mapper\TesterGroupAuthorisationMapper;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Dto\Person\PersonHelpDeskProfileDto;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\TestUtils\XMock;
use UserAdmin\Controller\UserProfileController;
use UserAdmin\Service\HelpdeskAccountAdminService;
use Zend\View\Model\ViewModel;
use DvsaCommon\HttpRestJson\Exception\ValidationException;
use Zend\Session\Container;

class UserProfileControllerTest extends AbstractFrontendControllerTestCase
{
    const PERSON_ID = 7;

    private $accountAdminServiceMock;
    private $authorisationMock;
    private $testerGroupAuthorisationMapper;

    public function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $serviceManager->setAllowOverride(true);
        $this->setServiceManager($serviceManager);

        $this->authorisationMock = XMock::of(MotAuthorisationServiceInterface::class);
        $this->accountAdminServiceMock = XMock::of(HelpdeskAccountAdminService::class);
        $this->testerGroupAuthorisationMapper = XMock::of(TesterGroupAuthorisationMapper::class);
        $this->testerGroupAuthorisationMapper->expects($this->any())
            ->method('getAuthorisation')
            ->willReturn(new TesterAuthorisation());

        $this->setController(
            new UserProfileController(
                $this->authorisationMock,
                $this->accountAdminServiceMock,
                $this->testerGroupAuthorisationMapper
            )
        );

        $this->getController()->setServiceLocator($serviceManager);

        $this->createHttpRequestForController('Reset');

        parent::setUp();
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
                    $this->{$mock['class']},
                    $mock['method'],
                    $this->once(),
                    $mock['result'],
                    $mock['params']
                );
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
        return [
            //  --  index: access action  --
            [
                'method' => 'get',
                'action' => 'index',
                'params' => [
                    'route' => [
                        'personId' => self::PERSON_ID,
                    ],
                ],
                'mocks' => [
                    [
                        'class'  => 'accountAdminServiceMock',
                        'method' => 'getUserProfile',
                        'params' => [self::PERSON_ID],
                        'result' => new PersonHelpDeskProfileDto(),
                    ],
                ],
                'expect' => [
                    'viewModel' => true,
                ],
            ],
            //  --  passwordReset: access action  --
            [
                'method' => 'post',
                'action' => 'passwordReset',
                'params' => [
                    'route' => [
                        'personId' => self::PERSON_ID,
                    ],
                    'post' => [],
                ],
                'mocks' => [
                    [
                        'class'  => 'accountAdminServiceMock',
                        'method' => 'getUserProfile',
                        'params' => [self::PERSON_ID],
                        'result' => new PersonHelpDeskProfileDto(),
                    ],
                ],
                'expect' => [
                    'viewModel' => true,
                ],
            ],
            //  --  usernameRecover: access action  --
            [
                'method' => 'post',
                'action' => 'usernameRecover',
                'params' => [
                    'route' => [
                        'personId' => self::PERSON_ID,
                    ],
                    'post' => [],
                ],
                'mocks' => [
                    [
                        'class'  => 'accountAdminServiceMock',
                        'method' => 'getUserProfile',
                        'params' => [self::PERSON_ID],
                        'result' => new PersonHelpDeskProfileDto(),
                    ],
                ],
                'expect' => [
                    'viewModel' => true,
                ],
            ],
            //  --  usernameRecover: post action Failure --
            [
                'method' => 'post',
                'action' => 'usernameRecover',
                'params' => [
                    'route' => [
                        'personId' => self::PERSON_ID,
                    ],
                    'post' => [],
                ],
                'mocks' => [
                    [
                        'class'  => 'accountAdminServiceMock',
                        'method' => 'getUserProfile',
                        'params' => [self::PERSON_ID],
                        'result' => new PersonHelpDeskProfileDto(),
                    ],
                    [
                        'class'  => 'accountAdminServiceMock',
                        'method' => 'postMessage',
                        'params' => [],
                        'result' => new ValidationException('/', 'post', [], 10, 'Person not found'),
                    ],
                ],
                'expect' => [
                    'viewModel' => true,
                ],
            ],
            //  --  claimAccount: access action  --
            [
                'method' => 'get',
                'action' => 'claimAccount',
                'params' => [
                    'route' => [
                        'personId' => self::PERSON_ID,
                    ],
                    'get' => [
                        'test' => 'test',
                    ],
                ],
                'mocks' => [
                    [
                        'class'  => 'accountAdminServiceMock',
                        'method' => 'getUserProfile',
                        'params' => [self::PERSON_ID],
                        'result' => new PersonHelpDeskProfileDto(),
                    ],
                ],
                'expect' => [
                    'viewModel' => true,
                ],
            ],
            //  --  claimAccount: post action  --
            [
                'method' => 'post',
                'action' => 'claimAccount',
                'params' => [
                    'route' => [
                        'personId' => self::PERSON_ID,
                    ],
                ],
                'mocks' => null,
                'expect' => [
                    'url' => '/user-admin/user-profile/' . self::PERSON_ID,
                ],
            ],
            //  --  claimAccount: post action Failed  --
            [
                'method' => 'post',
                'action' => 'claimAccount',
                'params' => [
                    'route' => [
                        'personId' => self::PERSON_ID,
                    ],
                ],
                'mocks' => [
                    [
                        'class'  => 'accountAdminServiceMock',
                        'method' => 'resetClaimAccount',
                        'params' => [self::PERSON_ID],
                        'result' => new \Exception('Person not found'),
                    ],
                ],
                'expect' => [
                    'url' => '/user-admin/user-profile/' . self::PERSON_ID,
                ],
            ],
            //  --  claimAccount: post multi data  --
            [
                'method'   => 'post',
                'action'   => 'claimAccount',
                'params' => [
                    'route' => [
                        'personId' => self::PERSON_ID,
                    ],
                    'post' => [
                        PrgHelper::FORM_GUID_FIELD_NAME => 'testToken'
                    ]
                ],
                'mocks'    => null,
                'expect'   => [
                    'url' => 'redirectUrl',
                ],
            ],
        ];
    }
}
