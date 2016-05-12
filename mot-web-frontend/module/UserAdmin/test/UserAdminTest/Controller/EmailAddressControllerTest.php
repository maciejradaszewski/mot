<?php

namespace UserAdminTest\Controller;

use CoreTest\Controller\AbstractFrontendControllerTestCase;
use Dvsa\Mot\Frontend\PersonModule\View\ContextProvider;
use Dvsa\Mot\Frontend\PersonModule\View\PersonProfileUrlGenerator;
use DvsaCommon\Auth\MotIdentityInterface;
use DvsaCommon\Auth\MotIdentityProvider;
use DvsaCommon\Model\TesterAuthorisation;
use DvsaClient\Mapper\TesterGroupAuthorisationMapper;
use DvsaClient\MapperFactory;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Constants\FeatureToggle;
use DvsaCommon\Dto\Person\PersonContactDto;
use DvsaCommon\Dto\Person\PersonHelpDeskProfileDto;
use DvsaCommon\UrlBuilder\UserAdminUrlBuilderWeb;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\TestUtils\XMock;
use UserAdmin\Controller\EmailAddressController;
use UserAdmin\Service\HelpdeskAccountAdminService;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

class EmailAddressControllerTest extends AbstractFrontendControllerTestCase
{
    const PERSON_ID = 105;
    const EMAIL     = 'emailaddresscontrollertest@dvsa.test';

    private $helpdeskAccountAdminServiceMock;
    private $authorisationMock;
    private $testerGroupAuthorisationMapper;

    /**
     * @var MapperFactory
     */
    private $mapperFactory;

    /**
     * @var PersonProfileUrlGenerator
     */
    private $personProfileUrlGenerator;

    /**
     * @var ContextProvider
     */
    private $contextProvider;

    private $motIdentityProviderMock;

    private $motIdentity;

    public function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $serviceManager->setAllowOverride(true);
        $this->setServiceManager($serviceManager);

        $this->authorisationMock = XMock::of(MotAuthorisationServiceInterface::class);
        $this->helpdeskAccountAdminServiceMock = XMock::of(HelpdeskAccountAdminService::class);
        $this->testerGroupAuthorisationMapper = XMock::of(TesterGroupAuthorisationMapper::class);
        $this->testerGroupAuthorisationMapper->expects($this->any())
            ->method('getAuthorisation')
            ->willReturn(new TesterAuthorisation());

        $this->mapperFactory = $this
            ->getMockBuilder(MapperFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->personProfileUrlGenerator = $this
            ->getMockBuilder(PersonProfileUrlGenerator::class)
            ->setMethods(['toPersonProfile'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->personProfileUrlGenerator->method('toPersonProfile')->willReturn('/your-profile');

        $this->contextProvider = $this
            ->getMockBuilder(ContextProvider::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->motIdentityProviderMock = $this
            ->getMockBuilder(MotIdentityProvider::class)
            ->disableOriginalConstructor()
            ->setMethods(['getIdentity', 'setZendAuthenticationService'])
            ->getMock();

        $motIdentityMock = $this
            ->getMockBuilder(MotIdentityInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getUserId'])
            ->getMockForAbstractClass();

        $this->serviceManager->setService('MotIdentityProvider', $this->motIdentityProviderMock);

        $motIdentityMock->method('getUserId')->willReturn(self::PERSON_ID);

        $this
            ->motIdentityProviderMock
            ->method('getIdentity')
            ->willReturn($motIdentityMock);

        $this
            ->contextProvider
            ->method('getContext')
            ->willReturn(ContextProvider::YOUR_PROFILE_CONTEXT);

        $this->setController(
            new EmailAddressController(
                $this->authorisationMock,
                $this->helpdeskAccountAdminServiceMock,
                $this->testerGroupAuthorisationMapper,
                $this->mapperFactory,
                $this->personProfileUrlGenerator,
                $this->contextProvider
            )
        );

        $this->getController()->setServiceLocator($serviceManager);
        $this->withFeatureToggles([FeatureToggle::NEW_PERSON_PROFILE => true]);

        $this->createHttpRequestForController('index');

        parent::setUp();
    }

    /**
     * @dataProvider dataProviderTestActionsResultAndAccess
     */
    public function testIndex($method, $action, $params, $mocks, $expect)
    {
        $result = null;

        $session = new Container('prgHelperSession');
        $session->offsetSet('testToken', 'redirectUrl');

        if ($mocks !== null) {
            foreach ($mocks as $mock) {
                $this->mockMethod(
                    $this->{$mock['class']},
                    $mock['method'],
                    isset($mock['with']) ? $mock['with'] : $this->once(),
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
            //  --  index: show form --
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
                        'class'  => 'helpdeskAccountAdminServiceMock',
                        'method' => 'getUserProfile',
                        'params' => [self::PERSON_ID],
                        'result' => new PersonHelpDeskProfileDto(),
                    ],
                ],
                'expect' => [
                    'viewModel' => true,
                ],
            ],
            //  --  index: submit  --
            [
                'method' => 'post',
                'action' => 'index',
                'params' => [
                    'route' => [
                        'personId' => self::PERSON_ID,
                    ],
                    'post' => ['email' => self::EMAIL, 'emailConfirm' => self::EMAIL],
                ],
                'mocks' => [
                    [
                        'class'  => 'helpdeskAccountAdminServiceMock',
                        'method' => 'getUserProfile',
                        'params' => [self::PERSON_ID],
                        'result' => new PersonHelpDeskProfileDto(),
                    ],
                    [
                        'class'  => 'helpdeskAccountAdminServiceMock',
                        'method' => 'updatePersonContactEmail',
                        'params' => [self::PERSON_ID, self::EMAIL],
                        'result' => new PersonContactDto(),
                    ],
                ],
                'expect' => [
                    'viewModel' => false,
                    'url'       => '/your-profile',
                ],
            ],
            // -- non-matching email fields --
            [
                'method' => 'post',
                'action' => 'index',
                'params' => [
                    'route' => [
                        'personId' => self::PERSON_ID,
                    ],
                    'post' => ['email' => self::EMAIL, 'emailConfirm' => self::EMAIL . 'wrong'],
                ],
                'mocks' => [
                    [
                        'class'  => 'helpdeskAccountAdminServiceMock',
                        'method' => 'getUserProfile',
                        'params' => [self::PERSON_ID],
                        'result' => new PersonHelpDeskProfileDto(),
                    ],
                    [
                        'class'  => 'helpdeskAccountAdminServiceMock',
                        'method' => 'updatePersonContactEmail',
                        'params' => [self::PERSON_ID, self::EMAIL],
                        'result' => new PersonContactDto(),
                        'with'   => $this->never(),
                    ],
                ],
                'expect' => [
                    'viewModel' => true,
                    'flashError' => ['emailConfirm' => "the email addresses you have entered don't match"],
                ],
            ],
            // -- blank email fields --
            [
                'method' => 'post',
                'action' => 'index',
                'params' => [
                    'route' => [
                        'personId' => self::PERSON_ID,
                    ],
                    'post' => ['email' => '', 'emailConfirm' => ''],
                ],
                'mocks' => [
                    [
                        'class'  => 'helpdeskAccountAdminServiceMock',
                        'method' => 'getUserProfile',
                        'params' => [self::PERSON_ID],
                        'result' => new PersonHelpDeskProfileDto(),
                    ],
                    [
                        'class'  => 'helpdeskAccountAdminServiceMock',
                        'method' => 'updatePersonContactEmail',
                        'params' => [self::PERSON_ID, self::EMAIL],
                        'result' => new PersonContactDto(),
                        'with'   => $this->never(),
                    ],
                ],
                'expect' => [
                    'viewModel' => true,
                    'flashError' => ['email' => 'must be a valid email address'],
                ],
            ],
        ];
    }
}
