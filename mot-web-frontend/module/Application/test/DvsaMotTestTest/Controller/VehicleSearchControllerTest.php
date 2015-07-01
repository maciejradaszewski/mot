<?php

namespace DvsaMotTestTest\Controller;

use Application\Service\CatalogService;
use Application\Service\ContingencySessionManager;
use CoreTest\Service\StubCatalogService;
use DvsaClient\Mapper\VehicleMapper;
use DvsaClient\MapperFactory;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Dto\Common\MotTestDto;
use DvsaCommon\Dto\Vehicle\History\VehicleHistoryDto;
use DvsaCommon\Dto\Vehicle\VehicleDto;
use DvsaCommon\Obfuscate\EncryptionKey;
use DvsaCommon\Obfuscate\ParamEncoder;
use DvsaCommon\Obfuscate\ParamEncrypter;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\Controller\StubIdentityAdapter;
use DvsaCommonTest\TestUtils\XMock;
use DvsaMotTest\Constants\VehicleSearchSource;
use DvsaMotTest\Controller\VehicleSearchController;
use DvsaMotTest\Model\VehicleSearchResult;
use DvsaMotTest\Service\VehicleSearchService;
use PHPUnit_Framework_MockObject_MockObject as MockObj;

/**
 * Class VehicleSearchControllerTest.
 */
class VehicleSearchControllerTest extends AbstractVehicleSearchControllerTest
{
    const MOT_TEST_NUMBER = 1;
    const MOT_TEST_INVALID_NUMBER = 999;
    const NO_REG_TRUE = 1;
    const NO_REG_FALSE = 0;

    protected $idToObfuscatedValueMap;

    /** VehicleMapper|@var MockObj */
    private $mockVehicleMapper;
    /** @var  ParamObfuscator|MockObj */
    private $mockParamObfuscator;

    private $mockVehicleSearchService;

    protected function setUp()
    {
        // patch for segmentation fault on jenkins
        gc_collect_cycles();
        gc_disable();

        $serviceManager = Bootstrap::getServiceManager();
        $serviceManager->setAllowOverride(true);

        $this->setServiceManager($serviceManager);

        $this->mockVehicleSearchService = XMock::of(VehicleSearchService::class);

        $this->setController(
            new VehicleSearchController(
                $this->mockVehicleSearchService,
                $this->createParamObfuscator(),
                new StubCatalogService(),
                $this->createVehicleSearchResultModel()
            )
        );

        $mockMapperFactory = $this->getMapperFactoryMock();
        $serviceManager->setService(MapperFactory::class, $mockMapperFactory);

        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
        gc_enable();
    }

    public function testVehicleSearchCanBeAccessedForAuthenticatedRequest()
    {
        parent::canBeAccessedForAuthenticatedRequest('vehicleSearch');
    }

    /**
     * @expectedException \DvsaCommon\Exception\UnauthorisedException
     */
    public function testSearchVehicleUnauthenticated()
    {
        parent::cantBeAccessedUnauthenticatedRequest('vehicleSearch');
    }

    public function testSearchVehicleWithValidPartialVinAndReg($action = null, $expUrl = null)
    {
        $this->setupAuthorizationService([PermissionInSystem::MOT_TEST_START]);
        $vehicleData = $this->getTestVehicleData();

        parent::testSearchVehicleWithValidPartialVinAndReg(
            '/start-test-confirmation/' . $vehicleData['id'] . '/0/' . VehicleSearchSource::VTR
        );
    }

    public function testSearchVehicleWithValidFullVinAndNoReg($action = null, $expUrl = null)
    {
        $this->setupAuthorizationService([PermissionInSystem::MOT_TEST_START]);
        $vehicleData = $this->getTestVehicleData();

        parent::testSearchVehicleWithValidFullVinAndNoReg(
            '/start-test-confirmation/' . $vehicleData['id'] . '/1/' . VehicleSearchSource::VTR
        );
    }

    public function testSearchVehicleWithFullVinWithSpacesAndNoReg($action = null, $expUrl = null)
    {
        $this->setupAuthorizationService([PermissionInSystem::MOT_TEST_START]);
        $vehicleData = $this->getTestVehicleData();

        parent::testSearchVehicleWithFullVinWithSpacesAndNoReg(
            '/start-test-confirmation/' . $vehicleData['id'] . '/1/' . VehicleSearchSource::VTR
        );
    }

    public function testSearchVehicleWithPartialVinAndNoReg($action = null)
    {
        $this->setupAuthorizationService([PermissionInSystem::MOT_TEST_START]);
        $this->getRestClientMock('getWithParams', $this->getNoneTestSearchResult());

        $getParams = [
            VehicleSearchController::PRM_VIN => self::TEST_PARTIAL_VIN,
            VehicleSearchController::PRM_REG => ""
        ];

        $result = $this->requestSearch($getParams, $action, 'get');

        $variables = $result->getVariables();
        $variables['form']->setData($getParams);

        $this->assertFormIsValid($variables);

        $this->assertResponseStatus(self::HTTP_OK_CODE);
    }

    public function testSearchVehicleWithPartialVinAndRegMultipleResults()
    {
        $this->setupAuthorizationService([PermissionInSystem::MOT_TEST_START]);

        $this->setResultForVehicleSearchService('search', $this->getTwoTestVehicleDataResultsViaVehicleSearchService());

        $getParams = [
            VehicleSearchController::PRM_VIN => self::TEST_PARTIAL_VIN,
            VehicleSearchController::PRM_REG => self::TEST_REG,
        ];

        $result = $this->requestSearch($getParams, null, 'get');

        $this->assertResponseStatus(self::HTTP_OK_CODE);

        $variables = $result->getVariables();
        $variables['form']->setData($getParams);

        $this->assertFormIsValid($variables);

        $this->assertVehiclesSearchModel($variables['results']);
        $this->assertEquals($variables['noMatches'], false);
    }

    private function assertVehicles($actualVehicles)
    {
        $expectVehicles = $this->translateDvlaFlagIntoSource($this->getTwoTestVehicleData());

        foreach ($expectVehicles as $idx => $expect) {
            $actual = $actualVehicles[$idx];

            //  check actual id is not empty
            $this->assertRegExp('/[A-Za-z]+[0-9-_]+/', $actual['id']);
            //  because id obfuscated
            $this->assertNotEquals($expect['id'], $actual['id']);

            unset($actual['id'], $expect['id']);

            $this->assertEquals($expect, $actual);
        }
    }

    private function assertVehiclesSearchModel($actualVehicles)
    {
        $expectVehicles = $this->getTwoTestVehicleDataResultsViaVehicleSearchService();

        foreach ($expectVehicles as $idx => $expect) {
            $actual = $actualVehicles[$idx];

            //  check actual id is not empty
            $this->assertNotEmpty($actual->getId());

            $this->assertEquals($expect, $actual);
        }
    }

    private function translateDvlaFlagIntoSource($data)
    {
        array_walk(
            $data,
            function (&$vehicle) {
                $vehicle['source'] = $vehicle['isDvla'] ? VehicleSearchSource::DVLA : VehicleSearchSource::VTR;
            }
        );

        return $data;
    }

    public function testSearchVehicleWithValidRegNoVinMultipleResults()
    {
        $this->setupAuthorizationService([PermissionInSystem::MOT_TEST_START]);

        $this->setResultForVehicleSearchService('search', $this->getTwoTestVehicleDataResultsViaVehicleSearchService());

        $this->getRestClientMock('getWithParams', $this->getMultipleTestSearchResult());

        $getParams = [
            VehicleSearchController::PRM_VIN => "",
            VehicleSearchController::PRM_REG => self::TEST_REG,
        ];

        $result = $this->requestSearch($getParams, null, 'get');

        $this->assertResponseStatus(self::HTTP_OK_CODE);

        $variables = $result->getVariables();
        $variables['form']->setData($getParams);

        $this->assertFormIsValid($variables);

        $this->assertVehiclesSearchModel($variables['results']);
        $this->assertEquals($variables['noMatches'], false);
    }

    public function testSearchVehicleWithFullVinRegNoResults()
    {
        $this->setupAuthorizationService([PermissionInSystem::MOT_TEST_START]);
        $this->getRestClientMock('getWithParams', $this->getNoneTestSearchResult());

        $this->requestSearch(
            [
                VehicleSearchController::PRM_VIN => self::TEST_FULL_VIN,
                VehicleSearchController::PRM_REG => self::TEST_REG,
            ]
        );

        $this->assertResponseStatus(self::HTTP_OK_CODE);
    }

    public function testSearchVehicleWithFullVinNoResults()
    {
        $this->setupAuthorizationService([PermissionInSystem::MOT_TEST_START]);
        $this->getRestClientMock('getWithParams', $this->getNoneTestSearchResult());

        $this->requestSearch(
            [
                VehicleSearchController::PRM_VIN => self::TEST_FULL_VIN,
                VehicleSearchController::PRM_REG => ""
            ]
        );

        $this->assertResponseStatus(self::HTTP_OK_CODE);
    }

    public function testSearchVehicleWithFullVinWithSpacesNoResults()
    {
        $this->setupAuthorizationService([PermissionInSystem::MOT_TEST_START]);
        $this->getRestClientMock('getWithParams', $this->getNoneTestSearchResult());

        $this->requestSearch(
            [
                VehicleSearchController::PRM_VIN => self::TEST_FULL_VIN_WITH_SPACES,
                VehicleSearchController::PRM_REG => ""
            ]
        );

        $this->assertResponseStatus(self::HTTP_OK_CODE);
    }

    public function testSearchVehicleWithNoVinAndNoReg()
    {
        $this->setupAuthorizationService([PermissionInSystem::MOT_TEST_START]);

        $this->requestSearch(
            [
                VehicleSearchController::PRM_VIN => "",
                VehicleSearchController::PRM_REG => ""
            ],
            null,
            'get'
        );

        $this->assertResponseStatus(self::HTTP_OK_CODE);
    }

    public function testRetestVehicleSearchCanBeAccessedForAuthenticatedRequest()
    {
        parent::canBeAccessedForAuthenticatedRequest('retestVehicleSearch');
    }

    /**
     * @expectedException \DvsaCommon\Exception\UnauthorisedException
     */
    public function testRetestSearchVehicleUnauthenticated()
    {
        parent::cantBeAccessedUnauthenticatedRequest('retestVehicleSearch');
    }

    public function testRetestSearchVehicleWithValidMotTestId()
    {
        $this->setupAuthorizationService([PermissionInSystem::MOT_TEST_START]);

        $motTestData = $this->getTestMotTestDataDto();
        /** @var MotTestDto $motTestDto */
        $motTestDto = $motTestData['data'];

        $this->setResultForVehicleSearchService('getVehicleFromMotTestCertificateForRetest', $motTestDto->getVehicle());

        $this->requestSearch(
            [
                VehicleSearchController::PRM_TEST_NR => self::MOT_TEST_NUMBER,
                VehicleSearchController::PRM_REG => null,
                VehicleSearchController::PRM_SUBMIT => 'Search',
            ],
            'retest-vehicle-search'
        );

        $this->assertRedirectLocation(
            $this->getController()->getResponse(),
            '/start-retest-confirmation/' . self::VEHICLE_ID_ENC . '/0'
        );

        $this->assertResponseStatus(self::HTTP_REDIRECT_CODE);
    }

    public function testRetestSearchVehicleWithInvalidMotTestId()
    {
        $this->setupAuthorizationService([PermissionInSystem::MOT_TEST_START]);
        $error = 'error';

        $this->setResultForVehicleSearchService('getVehicleFromMotTestCertificateForRetest', $error);

        $this->requestSearch(
            [
                VehicleSearchController::PRM_TEST_NR => self::MOT_TEST_INVALID_NUMBER,
                VehicleSearchController::PRM_SUBMIT => 'Search',
            ], 'retest-vehicle-search'
        );

        $this->assertResponseStatus(self::HTTP_OK_CODE);
    }

    public function testRetestSearchVehicleWithValidPartialVinAndReg()
    {
        $this->setupAuthorizationService([PermissionInSystem::MOT_TEST_START]);
        $this->getRestClientMock('getWithParams', $this->getPositiveRetestSearchResult());

        $this->requestSearch(
            [
                VehicleSearchController::PRM_VIN => self::TEST_PARTIAL_VIN,
                VehicleSearchController::PRM_REG => self::TEST_REG,
                VehicleSearchController::PRM_SUBMIT => 'Search',
            ], 'retest-vehicle-search'
        );

        $this->assertResponseStatus(self::HTTP_REDIRECT_CODE);
    }

    /**
     * @dataProvider historyActionsDataProvider
     */
    public function testHistoryActionsOk($action)
    {
        $this->setupAuthorizationService([PermissionInSystem::CERTIFICATE_READ]);

        $vehicleDto = new VehicleDto();
        $vehicleDto->setId(self::VEHICLE_ID);

        $this->mockVehicleMapper
            ->expects($this->once())
            ->method('getById')
            ->with(self::VEHICLE_ID)
            ->willReturn($vehicleDto);

        $this->getRestClientMock('get', ['data' => []]);

        $result = $this->getResultForAction($action, ['id' => self::VEHICLE_ID_ENC]);
        $variables = $result->getVariables();

        $this->assertResponseStatus(self::HTTP_OK_CODE);
        $this->assertEquals($vehicleDto, $variables['vehicle']);
        $this->assertInstanceOf(VehicleHistoryDto::class, $variables['vehicleHistory']);
    }

    public static function historyActionsDataProvider()
    {
        return [
            ['testHistory'],
            ['dvsaTestHistory'],
        ];
    }

    /**
     * @expectedException \DvsaCommon\Exception\UnauthorisedException
     */
    public function testHistoryUnauthenticatedRequestThrowsException()
    {
        $this->setupAuthenticationServiceForIdentity(StubIdentityAdapter::asAnonymous());

        $this->getResponseForAction('testHistory', ['id' => '1']);
    }

    protected function getPositiveMotTestSearchResult()
    {
        return [
            'data' => [
                'id' => 1,
            ],
        ];
    }

    protected function getMultipleTestSearchResult()
    {
        return [
            'data' => [
                'resultType' => VehicleSearchController::SEARCH_RESULT_MULTIPLE_MATCHES,
                'vehicles' => $this->getTwoTestVehicleData(),
            ],
        ];
    }

    protected function getNoneTestSearchResult()
    {
        return [
            'data' => [
                'resultType' => VehicleSearchController::SEARCH_RESULT_NO_MATCH,
                'vehicles' => []
            ],
        ];
    }

    protected function getTwoTestVehicleData()
    {
        $vehicleData = [
            [
                'id' => self::VEHICLE_ID,
                'registration' => 'CRZ 4545',
                'vin' => 100000000001111111,
                'vehicle_class' => '4',
                'make' => 'FORD',
                'model' => 'FOCUS ZETEC',
                'year' => 2011,
                'colour' => 'SILVER',
                'fuel_type' => 'P',
                'isDvla' => true,
                'emptyVinReason' => null,
                'emptyRegistrationReason' => null
            ],
            [
                'id' => 2,
                'registration' => 'CRZ 4545',
                'vin' => 100000000002222222,
                'vehicle_class' => '4',
                'make' => 'FORD',
                'model' => 'FOCUS ZETEC',
                'year' => 2011,
                'colour' => 'SILVER',
                'fuel_type' => 'P',
                'isDvla' => true,
                'emptyVinReason' => null,
                'emptyRegistrationReason' => null
            ],
        ];

        return $vehicleData;
    }

    protected function getTwoTestVehicleDataResultsViaVehicleSearchService()
    {
        $vehicles = $this->getTwoTestVehicleData();

        $vehicleSearchModel = new VehicleSearchResult($this->mockParamObfuscator, new VehicleSearchSource());

        $vehicleSearchModel = $vehicleSearchModel->addResults($vehicles);
        $vehicles = $vehicleSearchModel->getResults();

        return $vehicles;
    }

    protected function getTestMotTestData()
    {
        $vehicleData = (new VehicleDto())->setId(self::VEHICLE_ID);

        return [
            "data" => [
                "id" => 1,
                "vehicle" => $vehicleData,
                "reasons_for_rejection" => [['rfr-id' => 1], ['rfr-id' => 2]],
                "break_test_results" => [['break-result-id' => 1]],
            ],
        ];
    }

    private function getTestMotTestDataDto()
    {
        $vehicleData = (new VehicleDto())->setId(self::VEHICLE_ID);

        return [
            "data" => (new MotTestDto())
                ->setId(1)
                ->setVehicle($vehicleData)
                ->setReasonsForRejection([['rfr-id' => 1], ['rfr-id' => 2]])
                ->setBrakeTestResult([['break-result-id' => 1]]),
        ];
    }

    private function getMapperFactoryMock()
    {
        $factoryMapper = XMock::of(MapperFactory::class);
        $this->mockVehicleMapper = XMock::of(VehicleMapper::class);

        $map = [
            ['Vehicle', $this->mockVehicleMapper],
        ];

        $factoryMapper->expects($this->any())
            ->method('__get')
            ->will($this->returnValueMap($map));

        return $factoryMapper;
    }

    public function testContingencyVehicleSearchWithValidData()
    {
        $this->setupAuthorizationService([PermissionInSystem::MOT_TEST_START]);

        $this->routeMatch->setParam('action', 'vehicle-search');
        $this->request->setMethod('get');

        $get = $this->request->getQuery();
        $get->set('contingency', 1);

        /* @var ContingencySessionManager $contingencySessionManager */
        $contingencySessionManager = new ContingencySessionManager();
        $contingencySessionManager->createContingencySession([], 1);

        $this->getController()->dispatch($this->request);

        $this->assertResponseStatus(self::HTTP_OK_CODE);
    }

    public function testContingencyVehicleSearchHack()
    {
        $this->setupAuthorizationService([PermissionInSystem::MOT_TEST_START]);

        $this->routeMatch->setParam('action', 'vehicle-search');
        $this->request->setMethod('get');

        $get = $this->request->getQuery();
        $get->set('contingency', 1);

        $this->getController()->dispatch($this->request);

        $this->assertResponseStatus(self::HTTP_REDIRECT_CODE);
    }

    /**
     * @return ParamObfuscator
     */
    protected function createParamObfuscator()
    {
        $config = $this->getServiceManager()->get('Config');
        $paramEncrypter = new ParamEncrypter(new EncryptionKey($config['security']['obfuscate']['key']));
        $paramEncoder = new ParamEncoder();

        $this->mockParamObfuscator = $this->getMockBuilder(ParamObfuscator::class)
            ->setConstructorArgs([$paramEncrypter, $paramEncoder, $config])
            ->setMethods(['obfuscateEntry'])
            ->getMock();

        $this->mockMethod(
            $this->mockParamObfuscator,
            'obfuscateEntry',
            $this->any(),
            self::VEHICLE_ID_ENC,
            [ParamObfuscator::ENTRY_VEHICLE_ID, self::VEHICLE_ID]
        );

        return $this->mockParamObfuscator;
    }

    protected function createVehicleSearchResultModel()
    {
        $vehicleSearchResult = new VehicleSearchResult(
            $this->createParamObfuscator(),
            new VehicleSearchSource()
        );

        return $vehicleSearchResult;
    }

    protected function setResultForVehicleSearchService($method = 'search', $resultWithSearchMethod = false)
    {
        $this->mockVehicleSearchService->expects($this->any())
                                       ->method($method)
                                       ->willReturn($resultWithSearchMethod);
    }
}
