<?php

namespace UserAdminTest\Controller;

use Account\Service\SecurityQuestionService;
use Account\ViewModel\PasswordResetFormModel;
use Application\Helper\PrgHelper;
use CoreTest\Controller\AbstractFrontendControllerTestCase;
use DvsaClient\Entity\Person;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\UrlBuilder\UserAdminUrlBuilderWeb;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\Controller\StubIdentityAdapter;
use DvsaCommonTest\TestUtils\XMock;
use UserAdmin\Controller\SecurityQuestionController;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

/**
 * Class SecurityQuestionControllerTest
 *
 * @package UserAdminTest\Controller
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
    }

    /**
     * @dataProvider dataProviderTestActionsResultAndAccess
     */
    public function testActionsResultAndAccess($method, $action, $postData, $mocks, $expect)
    {
        $result = null;
        $session = new Container('prgHelperSession');
        $session->offsetSet('testToken', 'redirectUrl');

        $this->setupAuthenticationServiceForIdentity(StubIdentityAdapter::asEnforcement());
        $this->setupAuthorizationService([PermissionInSystem::SECURITY_QUESTION_READ_USER]);

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

        if (!empty($expect['errors'])) {
            $this->assertInstanceOf(ViewModel::class, $result);

            /** @var  PasswordResetFormModel $form */
            $form = $result->getVariable('viewModel');

            foreach ($expect['errors'] as $field => $error) {
                $this->assertEquals($error, $form->getError($field));
            }
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
                'mocks'    => [
                    [
                        'class'  => 'securityQuestionService',
                        'method' => 'getPerson',
                        'params' => [],
                        'result' => new Person(),
                    ]
                ],
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
                    ],
                    [
                        'class'  => 'securityQuestionService',
                        'method' => 'getUserId',
                        'params' => [],
                        'result' => self::PERSON_ID,
                    ],
                    [
                        'class'  => 'securityQuestionService',
                        'method' => 'getQuestionNumber',
                        'params' => [],
                        'result' => self::QUESTION_NUNMBER,
                    ]
                ],
                'expect'   => [
                    'url' => UserAdminUrlBuilderWeb::userProfileSecurityQuestion(
                        self::PERSON_ID,
                        self::QUESTION_NUNMBER + 1
                    ),
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
                        'method' => 'getUserId',
                        'params' => [],
                        'result' => self::PERSON_ID,
                    ],
                    [
                        'class'  => 'securityQuestionService',
                        'method' => 'getQuestionNumber',
                        'params' => [],
                        'result' => self::QUESTION_NUNMBER,
                    ],
                ],
                'expect'   => [
                    'url' => UserAdminUrlBuilderWeb::userProfileSecurityQuestion(
                        self::PERSON_ID,
                        self::QUESTION_NUNMBER
                    ),
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
