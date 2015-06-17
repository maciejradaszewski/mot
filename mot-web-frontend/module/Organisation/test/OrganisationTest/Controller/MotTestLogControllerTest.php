<?php

namespace Organisation\Controller;

use CoreTest\Controller\AbstractFrontendControllerTestCase;
use DvsaClient\Mapper\OrganisationMapper;
use DvsaClient\MapperFactory;
use DvsaCommon\Auth\NotLoggedInException;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Dto\Organisation\MotTestLogSummaryDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Dto\Search\MotTestSearchParamsDto;
use DvsaCommon\Dto\Search\SearchResultDto;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\HttpRestJson\Exception\RestApplicationException;
use DvsaCommon\Messages\DateErrors;
use DvsaCommon\Utility\DtoHydrator;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\Controller\StubIdentityAdapter;
use DvsaCommonTest\TestUtils\XMock;
use Organisation\ViewModel\MotTestLog\MotTestLogFormViewModel;
use Zend\Http\Response;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;

/**
 * Class MotTestLogControllerTest
 *
 * @package OrganisationTest\Controller
 */
class MotTestLogControllerTest extends AbstractFrontendControllerTestCase
{
    private static $AE_ID = 1;

    /** @var DtoHydrator */
    private $dtoHydrator;

    public function setUp()
    {
        $this->dtoHydrator = new DtoHydrator();

        $serviceManager = Bootstrap::getServiceManager();
        $serviceManager->setAllowOverride(true);
        $this->setServiceManager($serviceManager);

        $this->setController(new MotTestLogController());
        $this->getController()->setServiceLocator($serviceManager);

        $serviceManager->setService(MapperFactory::class, $this->getMapperFactory());

        $this->createHttpRequestForController('MotTestLog');

        parent::setUp();
    }

    /**
     * Test has user access to page or not with/out auth and permission
     *
     * @param string  $action          Request action
     * @param array   $params          Action parameters
     * @param array   $permissions     User has permissions
     * @param string  $expectedUrl     Expect redirect if failure
     *
     * @dataProvider dataProviderMotTestLogControllerTestCanAccessHasRight
     */
    public function testMotTestLogControllerCanAccessHasRight(
        $action,
        $params = [],
        $permissions = [],
        $expectedUrl = null
    ) {
        $this->setupAuthenticationServiceForIdentity(StubIdentityAdapter::asAedm());
        $this->setupAuthorizationService($permissions);

        $this->getRestClientMock('get', $this->getMotTestLogSummaryApiMock());

        $this->getResponseForAction($action, $params);

        if ($expectedUrl) {
            $this->assertRedirectLocation2($expectedUrl);
        } else {
            $this->assertResponseStatus(self::HTTP_OK_CODE);
        }
    }

    public function dataProviderMotTestLogControllerTestCanAccessHasRight()
    {
        $homeUrl = '/';

        return [
            ['index', [], [], $homeUrl],
            ['index', ['id' => self::$AE_ID], [PermissionAtOrganisation::AE_TEST_LOG], ],
            ['index', ['id' => self::$AE_ID], [PermissionInSystem::MOT_TEST_READ], $homeUrl],
            ['index', ['id' => self::$AE_ID], [], $homeUrl],
        ];
    }

    /**
     * Test creation of CVS file
     *
     * @throws \Exception
     */
    public function testDownloadCsv()
    {
        $apiResult = [
            '1' => [
                'testDateTime'  => '2014-12-10 23:59:59.000000',
                'emRecDateTime' => '2013-12-11 23:59:59.000000',
                'vehicleModel'  => '100',
                'emCode'        => null,
                'column11'      => 'row1 col1',
                'column12'      => 99999,
                'column13'      => 'row1 col2',
            ],
            '2' => [
                'testDateTime'  => '2014-12-10 23:59:59.000000',
                'emRecDateTime' => '2013-12-11 23:59:59.000000',
                'vehicleModel'  => 200,
                'emCode'        => '1',
                'column21'      => 'row2 col1',
                'column22'      => 88888,
                'column23'      => 'row2 col2',
            ],
            '3' => [
                'testDateTime'  => '2014-12-10 23:59:59.000000',
                'emRecDateTime' => null,
                'vehicleModel'  => '9-4',
                'emCode'        => '1',
                'column21'      => 'row2 col1',
                'column22'      => 88888,
                'column23'      => 'row2 col2',
            ],
        ];

        $expectCsvResult = array_replace_recursive(
            $apiResult,
            [
                1 => [
                    'testDateTime'  => '10/12/2014 23:59:59',
                    'emRecDateTime' => '11/12/2013 23:59:59',
                    'vehicleModel'  => '="100"'
                ],
                2 => [
                    'testDateTime'  => '10/12/2014',
                    'emRecDateTime' => '11/12/2013 23:59:59',
                    'vehicleModel'  => '="200"'
                ],
                3 => [
                    'testDateTime'  => '10/12/2014',
                    'emRecDateTime' => null,
                    'vehicleModel'  => '="9-4"'
                ],
            ]
        );

        $resultDto = new SearchResultDto();
        $resultDto
            ->setData($apiResult)
            ->setResultCount(count($apiResult))
            ->setTotalResultCount(9999);

        $paramsDto = $this->getSearchParams();

        //  --  mock    --
        $this->getRestClientMock('post', $this->getMotTestLogResultApiMock($resultDto));

        //  --  call    --
        /** @var Response $response */
        $response = XMock::invokeMethod($this->getController(), 'downloadCsv', [self::$AE_ID, $paramsDto]);

        //  ----  check   ----
        $this->assertResponseStatus(self::HTTP_OK_CODE, $response);

        //  -- prepare csv content and compare  --
        $csvBuffer = fopen('php://memory', 'w');
        foreach (([MotTestLogController::$CSV_COLUMNS] + $expectCsvResult) as $line) {
            fputcsv($csvBuffer, $line);
        }
        rewind($csvBuffer);
        $expectContent = stream_get_contents($csvBuffer);
        fclose($csvBuffer);

        $this->assertEquals($expectContent, $response->getContent());

        //  --  check headers   --
        $fileName = 'test-log-11122013-02032014.csv';

        $expectHeaders = [
            'Content-Type'        => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control'       => 'max-age=0, must-revalidate, no-cache, no-store',
            'Pragma'              => 'no-cache',
            'Content-Length'      => strlen($expectContent),
        ];

        $headers = $response->getHeaders();
        foreach ($expectHeaders as $name => $value) {
            $this->assertEquals($value, $headers->get($name)->getFieldValue());
        }
    }

    /**
     * Test class methods getLogDataBySearchCriteria and getLogSummary
     * @dataProvider dataProviderTestGetLogX
     */
    public function testGetMotLogX($httpMethod, $classMethod, $postResult, $expect)
    {
        $this->getRestClientMock($httpMethod, $postResult);

        $result = XMock::invokeMethod($this->getController(), $classMethod['name'], $classMethod['params']);

        if (!empty($expect['errorMsg'])) {
            $this->assertSame(
                $expect['errorMsg'],
                $this->controller->flashMessenger()->getCurrentErrorMessages()[0]
            );
        }
        $this->assertSame($expect['return'], $result);
    }

    public function dataProviderTestGetLogX()
    {
        $paramsDto = $this->getSearchParams();

        return [
            [
                'httpMethod' => 'post',
                'method' => [
                    'name' => 'getLogDataBySearchCriteria',
                    'params' => [self::$AE_ID, $paramsDto],
                ],
                'postResult' => $this->getMotTestLogResultApiMock(['RESULT']),
                'expect'     => [
                    'return' => ['RESULT'],
                ],
            ],
            [
                'httpMethod' => 'post',
                'method' => [
                    'name' => 'getLogDataBySearchCriteria',
                    'params' => [self::$AE_ID, $paramsDto],
                ],
                'postResult' => new RestApplicationException('/', 'post', [], 10, [['displayMessage' => 'ErrorText']]),
                'expect'     => [
                    'return'   => null,
                    'errorMsg' => 'ErrorText',
                ],
            ],
            [
                'httpMethod' => 'get',
                'method' => [
                    'name' => 'getLogSummary',
                    'params' => [self::$AE_ID],
                ],
                'postResult' => $this->getMotTestLogResultApiMock(['RESULT']),
                'expect'     => [
                    'return' => ['RESULT'],
                ],
            ],
            [
                'httpMethod' => 'get',
                'method' => [
                    'name' => 'getLogSummary',
                    'params' => [self::$AE_ID],
                ],
                'postResult' => new RestApplicationException('/', 'get', [], 10, [['displayMessage' => 'ErrorText']]),
                'expect'     => [
                    'return'   => null,
                    'errorMsg' => 'ErrorText',
                ],
            ],
        ];
    }

    /**
     * Test Error messages for different conditions
     *
     * @param array                $postData
     * @param SearchResultDto|null $apiResult
     * @param array                $expect
     *
     * @dataProvider dataProviderTestErrors
     */
    public function testErrors($postData, $apiResult, $expectErr)
    {
        $this->setupAuthenticationServiceForIdentity(StubIdentityAdapter::asAedm());
        $this->setupAuthorizationService([PermissionAtOrganisation::AE_TEST_LOG]);

        //  ----  mock    ----
        $this->getOrganisationMapperMock();

        $mockRestClient = $this->getRestClientMock('get', $this->getMotTestLogSummaryApiMock());
        if ($apiResult) {
            $this->mockMethod($mockRestClient, 'post', $this->any(), $this->getMotTestLogResultApiMock($apiResult));
        }

        //  --  error messages  --
        $flashMock = XMock::of(FlashMessenger::class);
        $this->getController()->getPluginManager()->setService('flashMessenger', $flashMock, false);

        foreach ($expectErr as $idx => $err) {
            $flashMock->expects($this->at($idx))
                ->method('addErrorMessage')
                ->with($err);
        }

        $flashMock->expects($this->once())
            ->method('getCurrentErrorMessages')
            ->willReturn($apiResult ? null : count($expectErr));

        //  --  request --
        $this->setGetAndQueryParams($postData + ['_csrf_token' => true]);
        $this->getResultForAction('index', ['id' => self::$AE_ID]);

        $this->assertResponseStatus(self::HTTP_OK_CODE);
    }

    public function dataProviderTestErrors()
    {
        $currDate = new \DateTime();
        $currYear = (int)$currDate->format('Y');

        $dateNull = ['Day' => null, 'Month' => null, 'Year' => null];
        $dateFrom = ['Day' => $currDate->format('j'), 'Month' => $currDate->format('n'), 'Year' => $currYear];
        $dateTo   = ['Day' => $currDate->format('j'), 'Month' => $currDate->format('n'), 'Year' => $currYear];

        $resultDto = new SearchResultDto();

        return [
            [
                'post'  => [
                    MotTestLogFormViewModel::FLD_DATE_FROM => $dateNull,
                    MotTestLogFormViewModel::FLD_DATE_TO   => ['Year'  => $currYear + 1] + $dateTo,
                ],
                'apiResult' => null,
                'expect' => [
                    '1' => sprintf(DateErrors::DATE_INVALID, 'From'),
                    '3' => sprintf(DateErrors::DATE_FUTURE, 'To'),
                ],
            ],
            [
                'post'  => [
                    MotTestLogFormViewModel::FLD_DATE_FROM => ['Year'  => $currYear + 1] + $dateFrom,
                    MotTestLogFormViewModel::FLD_DATE_TO   => $dateNull,
                ],
                'apiResult' => null,
                'expect' => [
                    '1' => sprintf(DateErrors::DATE_FUTURE, 'From'),
                    '3' => sprintf(DateErrors::DATE_INVALID, 'To'),
                ],
            ],
            [
                'post'  => [
                    MotTestLogFormViewModel::FLD_DATE_FROM => $dateFrom,
                    MotTestLogFormViewModel::FLD_DATE_TO   => ['Year'  => $currYear - 1] + $dateTo,
                ],
                'apiResult' => null,
                'expect' => [
                    '1' => sprintf(DateErrors::INCORRECT_INTERVAL, 'To', 'From'),
                ],
            ],
            [
                'post'  => [
                    MotTestLogFormViewModel::FLD_DATE_FROM => $dateFrom,
                    MotTestLogFormViewModel::FLD_DATE_TO   => $dateTo,
                ],
                'apiResult' => self::cloneObject($resultDto)->setTotalResultCount(0),
                'expect' => [
                    '3' => MotTestLogController::ERR_NO_DATA,
                ],
            ],
            [
                'post'  => [
                    MotTestLogFormViewModel::FLD_DATE_FROM => $dateFrom,
                    MotTestLogFormViewModel::FLD_DATE_TO   => $dateTo,
                ],
                'apiResult' => self::cloneObject($resultDto)->setTotalResultCount(51000),
                'expect' => [
                    '3' => sprintf(
                        MotTestLogController::ERR_TOO_MANY_RECORDS, 51000, MotTestLogController::MAX_TESTS_COUNT
                    ),
                ],
            ],
        ];
    }


    private function getSearchParams()
    {
        $paramsDto = new MotTestSearchParamsDto();
        $paramsDto
            ->setStatus([MotTestStatusName::PASSED])
            ->setDateFromTS((new \DateTime('2013-12-11'))->getTimestamp())
            ->setDateToTS((new \DateTime('2014-03-02'))->getTimestamp());

        return $paramsDto;
    }


    private function getMapperFactory()
    {
        $mockMapperFactory = XMock::of(MapperFactory::class);

        $map = [
            [MapperFactory::ORGANISATION, $this->getOrganisationMapperMock()],
        ];

        $mockMapperFactory->expects($this->any())
            ->method('__get')
            ->will($this->returnValueMap($map));

        return $mockMapperFactory;
    }

    private function getOrganisationMapperMock()
    {
        $orgDto = new OrganisationDto();
        $orgDto->setId(self::$AE_ID);

        $mapper = XMock::of(OrganisationMapper::class);

        $mapper->expects($this->any())
            ->method('getAuthorisedExaminer')
            ->with(self::$AE_ID)
            ->will($this->returnValue($orgDto));

        return $mapper;
    }

    private function getMotTestLogSummaryApiMock()
    {
        return ['data' => $this->dtoHydrator->extract(new MotTestLogSummaryDto())];
    }

    private function getMotTestLogResultApiMock($data)
    {
        return ['data' => $data];
    }

    /**
     * @return SearchResultDto
     */
    private static function cloneObject($obj)
    {
        return clone ($obj);
    }
}
