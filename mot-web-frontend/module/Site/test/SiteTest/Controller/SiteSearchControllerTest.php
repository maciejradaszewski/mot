<?php

namespace SiteTest\Controller;

use Account\Controller\SecurityQuestionController;
use Account\Service\SecurityQuestionService;
use Account\ViewModel\PasswordResetFormModel;
use Application\Helper\PrgHelper;
use CoreTest\Controller\AbstractFrontendControllerTestCase;
use DvsaClient\Mapper\VehicleTestingStationMapper;
use DvsaClient\MapperFactory;
use DvsaCommon\Dto\Site\SiteListDto;
use DvsaCommon\Dto\Site\SiteSearchDto;
use DvsaCommon\UrlBuilder\AccountUrlBuilderWeb;
use DvsaCommon\UrlBuilder\SiteUrlBuilderWeb;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\TestUtils\XMock;
use PHPUnit_Framework_MockObject_MockObject as MockObj;
use Site\Controller\SiteSearchController;
use Site\ViewModel\SiteSearchViewModel;
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
 * Class SiteSearchControllerTest
 *
 * @package SiteTest\Controller
 */
class SiteSearchControllerTest extends AbstractFrontendControllerTestCase
{
    const QUESTION_NUNMBER = 1;
    const PERSON_ID = 999999;
    const QUESTION_ONE = 'question1';
    const ANSWER = 'blah';


    protected $mapper;
    protected $siteMapper;

    protected function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $serviceManager->setAllowOverride(true);
        $this->setServiceManager($serviceManager);

        $this->mapper = $this->getMapperFactory();

        $this->setController(
            new SiteSearchController($this->mapper)
        );

        $this->getController()->setServiceLocator($serviceManager);

        parent::setUp();
    }

    /**
     * @dataProvider dataProviderTestActionsResultAndAccess
     */
    public function testActionsResultAndAccess($method, $action, $postData, $mocks, $expect)
    {
        $result = null;

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
            //  --  search: access action  --
            [
                'method'   => 'get',
                'action'   => 'search',
                'postData' => [],
                'mocks'    => [],
                'expect'   => [
                    'viewModel' => true,
                ],
            ],
            //  --  result: no post   --
            [
                'method'   => 'get',
                'action'   => 'result',
                'postData' => [],
                'mocks'    => [],
                'expect'   => [
                    'url' => SiteUrlBuilderWeb::search(),
                ],
            ],
            //  --  result: post with invalid data   --
            [
                'method'   => 'post',
                'action'   => 'result',
                'postData' => [],
                'mocks'    => [],
                'expect'   => [
                    'viewModel' => true,
                ],
            ],
            //  --  result: post with valid data Exact match   --
            [
                'method'   => 'post',
                'action'   => 'result',
                'postData' => [
                    SiteSearchViewModel::FIELD_SITE_NUMBER => 'v1234'
                ],
                'mocks'    => [
                    [
                        'class'  => 'siteMapper',
                        'method' => 'search',
                        'params' => [],
                        'result' => (new SiteListDto())->setTotalResult(1)->setSites([(new SiteSearchDto())->setId(1)]),
                    ]
                ],
                'expect'   => [
                    'url' => SiteUrlBuilderWeb::of(1),
                ],
            ],
            //  --  result: post with valid data No result   --
            [
                'method'   => 'post',
                'action'   => 'result',
                'postData' => [
                    SiteSearchViewModel::FIELD_SITE_NUMBER => 'v1234'
                ],
                'mocks'    => [
                    [
                        'class'  => 'siteMapper',
                        'method' => 'search',
                        'params' => [],
                        'result' => (new SiteListDto())->setTotalResult(0),
                    ]
                ],
                'expect'   => [
                    'viewModel' => true,
                ],
            ],
            //  --  result: post with valid data Multiple result   --
            [
                'method'   => 'post',
                'action'   => 'result',
                'postData' => [
                    SiteSearchViewModel::FIELD_SITE_NUMBER => 'v1234'
                ],
                'mocks'    => [
                    [
                        'class'  => 'siteMapper',
                        'method' => 'search',
                        'params' => [],
                        'result' => (new SiteListDto())->setTotalResult(2),
                    ]
                ],
                'expect'   => [
                    'viewModel' => true,
                ],
            ],
        ];
    }

    private function getMapperFactory()
    {
        $mapperFactory = XMock::of(MapperFactory::class);

        $this->siteMapper = XMock::of(VehicleTestingStationMapper::class);

        $map = [
            [MapperFactory::VEHICLE_TESTING_STATION, $this->siteMapper],
        ];

        $mapperFactory->expects($this->any())
            ->method('__get')
            ->will($this->returnValueMap($map));

        return $mapperFactory;
    }
}
