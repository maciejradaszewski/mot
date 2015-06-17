<?php

namespace UserAdminTest\Controller;

use CoreTest\Controller\AbstractFrontendControllerTestCase;
use DvsaCommon\Dto\Person\PersonHelpDeskProfileDto;
use DvsaCommon\UrlBuilder\UserAdminUrlBuilderWeb;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonTest\TestUtils\XMock;
use UserAdmin\Controller\ResetAccountClaimByPostController;
use UserAdmin\Service\HelpdeskAccountAdminService;
use Zend\View\Model\ViewModel;
use DvsaCommonTest\Bootstrap;
use DvsaCommon\HttpRestJson\Exception\ValidationException;

/**
 * Test for {@link ResetAccountClaimByPostController}.
 *
 * @method ResetAccountClaimByPostController sut()
 */
class ResetAccountClaimByPostControllerTest extends AbstractFrontendControllerTestCase
{

    const PERSON_ID = 13;
    const PERSON_USERNAME = 'toto';

    /** @var HelpdeskAccountAdminService|\PHPUnit_Framework_MockObject_MockObject */
    private $accountAdminServiceMock;

    public function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $serviceManager->setAllowOverride(true);
        $this->setServiceManager($serviceManager);

        $this->accountAdminServiceMock = XMock::of(HelpdeskAccountAdminService::class);

        $this->setController(
            new ResetAccountClaimByPostController($this->accountAdminServiceMock)
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
                    'post' => [],
                    'route' => [
                        'personId' => self::PERSON_ID,
                    ],
                ],
                'mocks'  => [
                    [
                        'class'  => 'accountAdminServiceMock',
                        'method' => 'getUserProfile',
                        'params' => self::PERSON_ID,
                        'result' => new PersonHelpDeskProfileDto(),
                    ],
                ],
                'expect' => [
                    'viewModel' => true,
                ],
            ],
            //  --  index: access action with Params  --
            [
                'method' => 'get',
                'action' => 'index',
                'params' => [
                    'post' => [],
                    'route' => [
                        'personId' => self::PERSON_ID,
                    ],
                    'get' => [
                        'personUsername' => self::PERSON_USERNAME,
                    ]
                ],
                'mocks'  => [
                    [
                        'class'  => 'accountAdminServiceMock',
                        'method' => 'getUserProfile',
                        'params' => self::PERSON_ID,
                        'result' => new PersonHelpDeskProfileDto(),
                    ],
                ],
                'expect' => [
                    'viewModel' => true,
                ],
            ],
            //  --  index: post action  --
            [
                'method' => 'post',
                'action' => 'index',
                'params' => [
                    'post' => [],
                    'route' => [
                        'personId' => self::PERSON_ID,
                    ],
                ],
                'mocks'  => [
                    [
                        'class'  => 'accountAdminServiceMock',
                        'method' => 'resetAccount',
                        'params' => self::PERSON_ID,
                        'result' => true,
                    ],
                ],
                'expect' => [
                    'url' => UserAdminUrlBuilderWeb ::userProfile(self::PERSON_ID),
                ],
            ],
            //  --  index: post action fail --
            [
                'method' => 'post',
                'action' => 'index',
                'params' => [
                    'post' => [],
                    'route' => [
                        'personId' => self::PERSON_ID,
                    ],
                ],
                'mocks'  => [
                    [
                        'class'  => 'accountAdminServiceMock',
                        'method' => 'resetAccount',
                        'params' => self::PERSON_ID,
                        'result' => new ValidationException('/', 'post', [], 10, [['displayMessage' => 'error']]),
                    ],
                ],
                'expect' => [
                    'url' => UserAdminUrlBuilderWeb ::userProfile(self::PERSON_ID),
                ],
            ],
        ];
    }
}
