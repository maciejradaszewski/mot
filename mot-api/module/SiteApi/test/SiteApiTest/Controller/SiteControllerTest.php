<?php

namespace SiteApiTest\Controller;

use DvsaCommon\Constants\Role;
use DvsaCommon\Dto\Site\SiteContactDto;
use DvsaCommon\Dto\Site\VehicleTestingStationDto;
use DvsaCommon\Utility\DtoHydrator;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaCommonApiTest\Controller\AbstractRestfulControllerTestCase;
use DvsaCommonTest\TestUtils\XMock;
use PHPUnit_Framework_MockObject_MockObject as MockObj;
use SiteApi\Controller\SiteController;
use SiteApi\Service\SiteService;
use Zend\Http\Header\ContentType;
use Zend\Stdlib\Parameters;

/**
 * Class SiteControllerTest
 *
 * @package SiteApiTest\Controller
 */
class SiteControllerTest extends AbstractRestfulControllerTestCase
{
    protected $vehicleTestingStationRepository;
    protected $vehicleTestingStationHydrator;

    const SITE_ID = 1;
    const SITE_NR = 'V1234';

    protected function setUp()
    {
        $this->setController(new SiteController());
        parent::setUp();
    }

    /**
     * Test method is accessible for call with valid parameters
     *
     * @param string $method        HTTP method
     * @param string $action        route action
     * @param array  $params        route parameters
     * @param array  $queryParams   query parameters
     * @param string $serviceMethod mocked service method
     * @param array  $serviceReturn service method will return
     * @param array  $expectResult  expected method result
     *
     * @dataProvider dataProviderTestWithValidParam
     */
    public function testWithValidParam(
        $method,
        $action,
        $params,
        $queryParams,
        $serviceMethod,
        $serviceReturn,
        $expectResult
    ) {
        $this->mockValidAuthorization(array(Role::VEHICLE_EXAMINER));

        $mockSiteService = $this->getMockSiteService();
        $this->setupMockForCalls($mockSiteService, $serviceMethod, $serviceReturn);

        $result = $this->getResultForAction($method, $action, $params, $queryParams);

        $this->assertResponseStatusAndResult(self::HTTP_OK_CODE, $expectResult, $result);
    }

    public function dataProviderTestWithValidParam()
    {
        $getSrvResult = [
            'id'         => self::SITE_ID,
            'siteNumber' => self::SITE_NR,
        ];
        $getExpect = $this->getTestResponse($getSrvResult);

        $getSrvResultDto = DtoHydrator::dtoToJson(new VehicleTestingStationDto());
        $getExpectDto = ['data' => $getSrvResultDto];

        $postSrvResult = ['id' => self::SITE_ID];
        $postExpect = ['data' => $postSrvResult];

        return [
            [
                'method'        => 'get',
                'action'        => null,
                'params'        => ['id' => self::SITE_ID],
                'queryParams'   => [],
                'serviceMethod' => 'getVehicleTestingStationData',
                'serviceReturn' => $getSrvResult,
                'expectResult'  => $getExpect,
            ],
            [
                'get',
                null,
                ['id' => self::SITE_ID],
                ['dto' => true],
                'getVehicleTestingStationData',
                $getSrvResultDto,
                $getExpectDto,
            ],

            ['get', 'siteById', ['id' => self::SITE_ID], [], 'getSiteData', $getSrvResult, $getExpect],
            [
                'get',
                'findBySiteNumber',
                ['sitenumber' => self::SITE_NR],
                [],
                'getVehicleTestingStationDataBySiteNumber',
                $getSrvResult,
                $getExpect,
            ],
            [
                'get',
                'findBySiteNumber',
                ['sitenumber' => self::SITE_NR],
                ['dto' => true],
                'getVehicleTestingStationDataBySiteNumber',
                'serviceReturn' => $getSrvResultDto,
                'expectResult'  => $getExpectDto,
            ],
            ['put', null, ['id' => self::SITE_ID, 'data' => []], [], 'update', $postSrvResult, $postExpect],
            ['post', null, ['data' => []], [], 'create', $postSrvResult, $postExpect],
        ];
    }

    /**
     * Test service method return errors when call with invalid parameters
     *
     * @param string $method        HTTP method
     * @param string $action        route action
     * @param string $serviceMethod mocked service method
     * @param string $paramName     set value for parameter with name
     *
     * @dataProvider dataProviderTestWithInvalidParams
     */
    public function testWithInvalidParams($method, $action, $serviceMethod, $paramName)
    {
        $this->mockValidAuthorization(array(Role::VEHICLE_EXAMINER));

        $paramValue = 'notExists';

        $mockSiteService = $this->getMockSiteService();
        $exception = new NotFoundException('Site', $paramValue);
        $this->setupMockForCalls($mockSiteService, $serviceMethod, $exception, $paramValue);

        $this->setExpectedException(
            NotFoundException::class,
            sprintf(NotFoundException::ERROR_MSG_NOT_FOUND, 'Site', ' ' . $paramValue),
            NotFoundException::ERROR_CODE_NOT_FOUND
        );

        $this->getResultForAction($method, $action, [$paramName => $paramValue]);
    }

    public function dataProviderTestWithInvalidParams()
    {
        return [
            [
                'method'        => 'get',
                'action'        => null,
                'serviceMethod' => 'getVehicleTestingStationData',
                'paramName'     => 'id',
            ],
            ['get', 'siteById', 'getSiteData', 'id'],
            ['get', 'findBySiteNumber', 'getVehicleTestingStationDataBySiteNumber', 'sitenumber'],
            ['put', null, 'update', 'id'],
        ];
    }

    /**
     * Test service method return errors when required parameter is null
     *
     * @param string $method      HTTP method
     * @param string $action      route action
     * @param string $paramName   set value for parameter with name
     * @param string $expectError error
     *
     * @dataProvider dataProviderTestParamNull
     */
    public function testParamNull($method, $action, $paramName, $expectError)
    {
        $this->mockValidAuthorization(array(Role::VEHICLE_EXAMINER));

        $result = $this->getResultForAction($method, $action, [$paramName => null]);

        $this->assertResultHasErrors($result, [$expectError]);
    }

    public function dataProviderTestParamNull()
    {
        $errSiteId = [
            'message' => SiteController::SITE_ID_REQUIRED_MESSAGE,
            'code'    => SiteController::ERROR_CODE_REQUIRED,
        ];

        $errSiteNr = [
            'message' => SiteController::SITE_NUMBER_REQUIRED_MESSAGE,
            'code'    => SiteController::ERROR_CODE_REQUIRED,
        ];

        return [
            [
                'method'        => 'get',
                'action'        => null,
                'paramName'     => 'id',
                'expectError'   => $errSiteId,
            ],
            ['get', 'siteById', 'id', $errSiteId],
            ['get', 'findBySiteNumber', 'sitenumber', $errSiteNr],
        ];
    }

    public function testGetService()
    {
        $this->assertEquals(
            $this->getMockSiteService(),
            XMock::invokeMethod($this->getController(), 'getSiteService')
        );
    }

    protected function getQueryTestResponse($siteData)
    {
        $response = $this->getTestResponse($siteData);
        $response['data']['resultCount'] = 1;

        return $response;
    }

    protected function getTestResponse($siteData)
    {
        return [
            'data' => [
                'vehicleTestingStation' => $siteData
            ]
        ];
    }

    private function getMockSiteService()
    {
        return $this->getMockServiceManagerClass(SiteService::class, SiteService::class);
    }
}
