<?php

namespace DashboardTest\Controller;

use Dashboard\Controller\SecurityQuestionController;
use Account\Service\SecurityQuestionService;
use Application\Helper\PrgHelper;
use CoreTest\Controller\AbstractFrontendControllerTestCase;
use DvsaCommon\UrlBuilder\AccountUrlBuilderWeb;
use DvsaCommon\UrlBuilder\PersonUrlBuilderWeb;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\Controller\StubIdentityAdapter;
use DvsaCommonTest\TestUtils\XMock;
use PHPUnit_Framework_MockObject_MockObject as MockObj;
use Zend\View\Model\ViewModel;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Mvc\Router\RouteMatch;
use Zend\Session\Container;
use Zend\Stdlib\Parameters;
use DvsaCommon\HttpRestJson\Exception\NotFoundException;

/**
 * Class SecurityQuestionControllerTest
 *
 * @package DashboardTest\Controller
 */
class SecurityQuestionControllerTest extends AbstractFrontendControllerTestCase
{
    const QUESTION_NUNMBER = 1;
    const PERSON_ID = 999999;
    const QUESTION_ONE = 'question1';
    const ANSWER = 'blah';


    protected $securityQuestionService;

    protected function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $serviceManager->setAllowOverride(true);
        $this->setServiceManager($serviceManager);

        $this->securityQuestionService = XMock::of(SecurityQuestionService::class);

        $this->setController(
            new SecurityQuestionController($this->securityQuestionService)
        );

        $this->getController()->setServiceLocator($serviceManager);

        $this->createHttpRequestForController('SecurityQuestion');

        parent::setUp();

        $this->setupAuthenticationServiceForIdentity(StubIdentityAdapter::asTester(self::PERSON_ID));
    }

    /**
     * @dataProvider dataProviderTestActionsResultAndAccess
     */
    public function testActionsResultAndAccess($method, $action, $postData, $mocks, $expect)
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
            $this->setExpectedException($exception['class'], $exception['message']);
        }

        $result = $this->getResultForAction2($method, $action, null, null, $postData);

        //  --  check   --
        if (!empty($expect['viewModel'])) {
            $this->assertInstanceOf(ViewModel::class, $result);
            $this->assertResponseStatus(self::HTTP_OK_CODE);

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
                'method'   => 'get',
                'action'   => 'index',
                'postData' => [],
                'mocks'    => [],
                'expect'   => [
                    'viewModel' => true,
                ],
            ],
            //  --  index: post with valid data    --
            [
                'method'   => 'post',
                'action'   => 'index',
                'postData' => [
                    self::QUESTION_ONE => self::ANSWER,
                ],
                'mocks'    => [
                    [
                        'class'  => 'securityQuestionService',
                        'method' => 'manageSessionQuestion',
                        'params' => [],
                        'result' => true,
                    ]
                ],
                'expect'   => [
                    'url' => AccountUrlBuilderWeb::forgottenPasswordNotAuthenticated(),
                ],
            ],
            //  --  index: post with invalid data  --
            [
                'method'   => 'post',
                'action'   => 'index',
                'postData' => [
                    self::QUESTION_ONE => self::ANSWER,
                ],
                'mocks'    => [
                    [
                        'class'  => 'securityQuestionService',
                        'method' => 'manageSessionQuestion',
                        'params' => [],
                        'result' => false,
                    ],
                    [
                        'class'  => 'securityQuestionService',
                        'method' => 'getQuestionNumber',
                        'params' => [],
                        'result' => self::QUESTION_NUNMBER,
                    ],
                ],
                'expect'   => [
                    'url' => PersonUrlBuilderWeb::securityQuestions(
                        self::QUESTION_NUNMBER
                    ),
                ],
            ],
            //  --  index: get with error get question  --
            [
                'method'   => 'get',
                'action'   => 'index',
                'postData' => [
                    self::QUESTION_ONE => self::ANSWER,
                ],
                'mocks'    => [
                    [
                        'class'  => 'securityQuestionService',
                        'method' => 'manageSessionQuestion',
                        'params' => [],
                        'result' => false,
                    ],
                    [
                        'class'  => 'securityQuestionService',
                        'method' => 'getQuestion',
                        'params' => [],
                        'result' => new NotFoundException('/', 'post', [], 10, 'Question not found'),
                    ],
                ],
                'expect'   => [
                    'url' => AccountUrlBuilderWeb::forgottenPasswordNotAuthenticated(),
                ],
            ],
            //  --  index: post multi data  --
            [
                'method'   => 'post',
                'action'   => 'index',
                'postData' => [
                    self::QUESTION_ONE => self::ANSWER,
                    PrgHelper::FORM_GUID_FIELD_NAME => 'testToken'
                ],
                'mocks'    => [],
                'expect'   => [
                    'url' => 'redirectUrl',
                ],
            ],
        ];
    }
}
