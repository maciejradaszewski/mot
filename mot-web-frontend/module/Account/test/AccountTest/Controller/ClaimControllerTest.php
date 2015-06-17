<?php

namespace AccountTest\Controller;

use Account\Controller\ClaimController;
use Account\Service\ClaimAccountService;
use Account\Validator\ClaimValidator;
use CoreTest\Controller\AbstractFrontendControllerTestCase;
use DvsaCommon\UrlBuilder\AccountUrlBuilderWeb;
use DvsaCommon\UrlBuilder\PersonUrlBuilderWeb;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\Controller\StubIdentityAdapter;
use DvsaCommonTest\TestUtils\XMock;
use PHPUnit_Framework_MockObject_MockObject as MockObj;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Mvc\Router\RouteMatch;
use Zend\Session\Container;
use Zend\Stdlib\Parameters;
use Zend\View\Model\ViewModel;

/**
 * Class ClaimControllerTest
 */
class ClaimControllerTest extends AbstractFrontendControllerTestCase
{
//    protected $session;
    /** @var  ClaimAccountService|MockObj */
    private $mockClaimAccountSrv;
    /** @var  ClaimValidator|MockObj */
    private $mockClaimValidator;
    /** @var  array */
    private $config;

    protected function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $serviceManager->setAllowOverride(true);
        $this->setServiceManager($serviceManager);

        $this->mockSession = XMock::of(Container::class);

        $this->mockClaimAccountSrv = XMock::of(ClaimAccountService::class);
        $this->mockMethod($this->mockClaimAccountSrv, 'getSession', null, $this->mockSession);

        $this->mockClaimValidator = XMock::of(ClaimValidator::class);
        $this->config = [
            'helpdesk' => [],
        ];

        $this->setController(
            new ClaimController(
                $this->mockClaimAccountSrv,
                $this->mockClaimValidator,
                $this->config
            )
        );

        $this->getController()->setServiceLocator($serviceManager);

        $this->createHttpRequestForController('Claim');

        parent::setUp();

        $this->setupAuthenticationServiceForIdentity(StubIdentityAdapter::asTester());
    }

    public function _testIndexActionCanBeAccessed()
    {
        $this->getResultForAction2('get', 'index');

        $this->assertResponseStatus(self::HTTP_OK_CODE);
    }



    /**
     * @dataProvider dataProviderTestActionsResultAndAccess
     */
    public function testActionsResultAndAccess($method, $action, $params, $mocks, $expect)
    {
        $result = null;

        if ($mocks !== null) {
            foreach ($mocks as $mock) {
                $this->mockMethod(
                    $this->{$mock['class']},
                    $mock['method'],
                    ArrayUtils::tryGet($mock, 'invocation', $this->once()),
                    ArrayUtils::tryGet($mock, 'result'),
                    ArrayUtils::tryGet($mock, 'params')
                );
            }
        }

        //  --  set expected exception  --
        if (!empty($expect['exception'])) {
            $exception = $expect['exception'];
            $this->setExpectedException($exception['class'], $exception['message']);
        }

        $result = $this->getResultForAction2(
            $method, $action, ArrayUtils::tryGet($params, 'route'), null, ArrayUtils::tryGet($params, 'post')
        );

        //  --  check   --
        if (!empty($expect['viewModel'])) {
            $this->assertInstanceOf(ViewModel::class, $result);
            $this->assertResponseStatus(self::HTTP_OK_CODE);

        }
/*
        if (!empty($expect['errors'])) {
            / ** @var  PasswordResetFormModel $form * /
            $form = $result->getVariable('viewModel');

            foreach ($expect['errors'] as $field => $error) {
                $this->assertEquals($error, $form->getError($field));
            }
        }
*/
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
        $postData = [
            'field1' => 'value1',
        ];

        $postParameters = new Parameters();
        $postParameters->fromArray($postData);

        $sessionData = [
            ClaimController::STEP_1_NAME => [],
            ClaimController::STEP_2_NAME => [],
        ];

        return [
            //  --  reset: access action  --
            [
                'method' => 'get',
                'action' => 'reset',
                'params' => [],
                'mocks'  => [
                    [
                        'class'  => 'mockClaimAccountSrv',
                        'method' => 'clearSession',
                    ],
                ],
                'expect' => [
                    'url' => AccountUrlBuilderWeb::claimEmailAndPassword(),
                ],
            ],

            //  --  confirmEmailAndPassword: get: access action  --
            [
                'method' => 'get',
                'action' => 'confirmEmailAndPassword',
                'params' => [],
                'mocks'  => [],
                'expect' => [
                    'viewModel' => true,
                ],
            ],

            //  --  confirmEmailAndPassword: post: validation failed  --
            [
                'method' => 'post',
                'action' => 'confirmEmailAndPassword',
                'params' => [
                    'post' => $postData,
                ],
                'mocks'  => [
                    [
                        'class'  => 'mockClaimValidator',
                        'method' => 'validateStep',
                        'params' => [ClaimController::STEP_1_NAME, $postParameters],
                        'result' => false,
                    ],
                ],
                'expect' => [
                    'viewModel' => true,
                ],
            ],

            //  --  confirmEmailAndPassword: post: validation success --
            [
                'method' => 'post',
                'action' => 'confirmEmailAndPassword',
                'params' => [
                    'post' => $postData,
                ],
                'mocks'  => [
                    [
                        'class'  => 'mockClaimValidator',
                        'method' => 'validateStep',
                        'params' => [ClaimController::STEP_1_NAME, $postParameters],
                        'result' => true,
                    ],
                ],
                'expect' => [
                    'url' => AccountUrlBuilderWeb::claimSecurityQuestions(),
                ],
            ],

            //  --  setSecurityQuestion: get: come back to STEP1 because not stored --
            [
                'method' => 'get',
                'action' => 'setSecurityQuestion',
                'params' => [],
                'mocks'  => [
                    [
                        'class'  => 'mockClaimAccountSrv',
                        'method' => 'isStepRecorded',
                        'params' => [ClaimController::STEP_1_NAME],
                        'result' => false,
                    ],
                ],
                'expect' => [
                    'url' => AccountUrlBuilderWeb::claimEmailAndPassword(),
                ],
            ],

            //  --  setSecurityQuestion: get: access action  --
            [
                'method' => 'get',
                'action' => 'setSecurityQuestion',
                'params' => [],
                'mocks'  => [
                    [
                        'class'  => 'mockClaimAccountSrv',
                        'method' => 'isStepRecorded',
                        'params' => [ClaimController::STEP_1_NAME],
                        'result' => true,
                    ],
                ],
                'expect' => [
                    'viewModel' => true,
                ],
            ],

            //  --  setSecurityQuestion: post: validation failed  --
            [
                'method' => 'post',
                'action' => 'setSecurityQuestion',
                'params' => [
                    'post' => $postData,
                ],
                'mocks'  => [
                    [
                        'class'  => 'mockClaimAccountSrv',
                        'method' => 'isStepRecorded',
                        'params' => [ClaimController::STEP_1_NAME],
                        'result' => true,
                    ],
                    [
                        'class'  => 'mockClaimValidator',
                        'method' => 'validateStep',
                        'params' => [ClaimController::STEP_2_NAME, $postParameters],
                        'result' => false,
                    ],
                ],
                'expect' => [
                    'viewModel' => true,
                ],
            ],

            //  --  setSecurityQuestion: post: validation success --
            [
                'method' => 'post',
                'action' => 'setSecurityQuestion',
                'params' => [
                    'post' => $postData,
                ],
                'mocks'  => [
                    [
                        'class'  => 'mockClaimValidator',
                        'method' => 'validateStep',
                        'params' => [ClaimController::STEP_2_NAME, $postParameters],
                        'result' => true,
                    ],
                ],
                'expect' => [
                    'url' => AccountUrlBuilderWeb::claimGeneratePin(),
                ],
            ],


            //  --  generatePin: get: come back to STEP1 because not stored --
            [
                'method' => 'get',
                'action' => 'generatePin',
                'params' => [],
                'mocks'  => [
                    [
                        'class'  => 'mockClaimAccountSrv',
                        'method' => 'isStepRecorded',
                        'params' => [ClaimController::STEP_1_NAME],
                        'result' => false,
                    ],
                ],
                'expect' => [
                    'url' => AccountUrlBuilderWeb::claimEmailAndPassword(),
                ],
            ],

            //  --  generatePin: get: access action  --
            [
                'method' => 'get',
                'action' => 'generatePin',
                'params' => [],
                'mocks' => [
                    [
                        'class'      => 'mockClaimAccountSrv',
                        'method'     => 'isStepRecorded',
                        'invocation' => $this->any(),
                        'result'     => true,
                    ],
                ],
                'expect' => [
                    'viewModel' => true,
                ],
            ],

            //  --  generatePin: post: validation failed  --
            [
                'method' => 'post',
                'action' => 'generatePin',
                'params' => [],
                'mocks'  => [
                    [
                        'class'  => 'mockSession',
                        'method' => 'getArrayCopy',
                        'result' => $sessionData,
                    ],
                    [
                        'class'  => 'mockClaimValidator',
                        'method' => 'validateStep',
                        'invocation' => $this->any(),
                        'result' => $this->returnValueMap(
                            [
                                [ClaimController::STEP_1_NAME, $sessionData[ClaimController::STEP_1_NAME], true, true],
                                [ClaimController::STEP_2_NAME, $sessionData[ClaimController::STEP_2_NAME], true, false],
                            ]
                        ),
                    ],
                    [
                        'class'  => 'mockClaimValidator',
                        'method' => 'getMessages',
                        'invocation' => $this->any(),
                        'result' => [],
                    ],

                    [
                        'class'  => 'mockClaimAccountSrv',
                        'method' => 'isStepRecorded',
                        'invocation' => $this->any(),
                        'result' => true,
                    ],
                ],
                'expect' => [
                    'viewModel' => true,
                ],
            ],

            //  --  generatePin: post: both step validation success, send to api and goto home  --
            [
                'method' => 'post',
                'action' => 'generatePin',
                'params' => [
                    'post' => $postData,
                ],
                'mocks'  => [
                    [
                        'class'  => 'mockSession',
                        'method' => 'getArrayCopy',
                        'result' => $sessionData,
                    ],
                    [
                        'class'  => 'mockClaimValidator',
                        'method' => 'validateStep',
                        'invocation' => $this->any(),
                        'result' => true,
                    ],
                    [
                        'class'  => 'mockClaimValidator',
                        'method' => 'getMessages',
                        'invocation' => $this->any(),
                        'result' => [],
                    ],

                    [
                        'class'  => 'mockClaimAccountSrv',
                        'method' => 'isStepRecorded',
                        'invocation' => $this->any(),
                        'result' => true,
                    ],
                ],
                'expect' => [
                    'url' => PersonUrlBuilderWeb::home(),
                ],
            ],
        ];
    }
}
