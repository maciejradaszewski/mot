<?php

namespace VehicleTest\Controller;

use DvsaClient\Mapper\VehicleMapper;
use DvsaClient\MapperFactory;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Dto\Vehicle\VehicleDto;
use DvsaCommon\Obfuscate\EncryptionKey;
use DvsaCommon\Obfuscate\ParamEncoder;
use DvsaCommon\Obfuscate\ParamEncrypter;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaCommon\UrlBuilder\VehicleUrlBuilderWeb;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\Controller\StubIdentityAdapter;
use DvsaCommonTest\TestUtils\XMock;
use DvsaMotTestTest\Controller\AbstractDvsaMotTestTestCase;
use Vehicle\Controller\VehicleController;
use Zend\View\Model\ViewModel;

/**
 * Class VehicleControllerTest.
 */
class VehicleControllerTest extends AbstractDvsaMotTestTestCase
{
    /**
     * @var array
     */
    protected static $obfuscationMap = [
        1    => 'unit_obfuscate_id_1',
        2    => 'unit_obfuscate_id_2',
        999  => 'unit_obfuscate_id_999',
        1234 => 'unit_obfuscate_id_1234',
    ];

    protected $mockVehicleMapper;

    protected function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $serviceManager->setAllowOverride(true);
        $this->setServiceManager($serviceManager);
        $paramObfuscator = $this->createParamObfuscatorMock(self::$obfuscationMap);
        $this->setController(new VehicleController($paramObfuscator));
        $this->getController()->setServiceLocator($serviceManager);

        $serviceManager->setService(MapperFactory::class, $this->getMockMapperFactory());

        $this->createHttpRequestForController('Vehicle');

        parent::setUp();
    }

    /**
     * Test has user access to page or not with/out auth and permission.
     *
     * @param string  $method          Http method
     * @param string  $action          Request action
     * @param array   $params          Action parameters
     * @param array   $permissions     User has permissions
     * @param string  $expectedUrl     Expect location
     *
     * @dataProvider dataProviderTestCanAccessHasRight
     */
    public function testCanAccessHasRight(
        $method,
        $action,
        $params = [],
        $permissions = [],
        $expectedUrl = null
    ) {
        $this->request->setMethod($method);

        $this->setupAuthenticationServiceForIdentity(StubIdentityAdapter::asTester());
        $this->setupAuthorizationService($permissions);

        $this->getResultForAction($action, $params);

        if ($expectedUrl) {
            $this->assertRedirectLocation2($expectedUrl);
        } else {
            $this->assertResponseStatus(self::HTTP_OK_CODE);
        }
    }

    /**
     * @return array
     */
    public function dataProviderTestCanAccessHasRight()
    {
        $homeUrl   = '/';
        $urlSearch = VehicleUrlBuilderWeb::vehicle()->search();

        return [
            ['get', 'index', [], [],$homeUrl],
            [
                'get',
                'index',
                ['id' => self::$obfuscationMap[999]],
                [PermissionInSystem::FULL_VEHICLE_MOT_TEST_HISTORY_VIEW],
            ],
            ['get', 'search', [], [], $homeUrl],
            ['get', 'search', [], [PermissionInSystem::VEHICLE_READ]],
            ['get', 'result', [], [], $homeUrl],
            ['get', 'result', [], [PermissionInSystem::VEHICLE_READ], $urlSearch],
        ];
    }

    /**
     * This function is responsible to test that the action Result
     *  - display the Data-Table result in case there's multiple match;
     *  - redirect to the detail page in case of there's only one match;
     *  - redirect to the search page in case of there's no match;
     *  - redirect to the search page in case of there no post data;.
     *
     * @param array $postParams
     * @param mixed $searchResult
     * @param array $expect
     *
     * @dataProvider dataProviderTestPostResultWithDifferentResult
     */
    public function testPostResultWithDifferentResult($postParams, $searchResult, $expect)
    {
        $this->setupAuthenticationServiceForIdentity(StubIdentityAdapter::asTester());
        $this->setupAuthorizationService([PermissionInSystem::VEHICLE_READ]);

        //  Mock
        if ($searchResult) {
            $restController = $this->getRestClientMockForServiceManager();
            $restController->expects($this->at(0))
                ->method('getWithParams')
                ->willReturn($searchResult);
        }

        //  Request
        if ($searchResult) {
            $this->setPostAndPostParams($postParams);
        }
        $result = $this->getResultForAction('result');

        //  Check
        $expectStatus = ArrayUtils::tryGet($expect, 'status', false);
        if ($expectStatus) {
            $this->assertResponseStatus($expectStatus);
        }

        $expectInstanceOf = ArrayUtils::tryGet($expect, 'instanceOf', false);
        if ($expectInstanceOf) {
            $this->assertInstanceOf($expectInstanceOf, $result);
        }

        $expectUrl = ArrayUtils::tryGet($expect, 'url', false);
        if ($expectUrl) {
            $this->assertRedirectLocation2($expectUrl);
        }
    }

    /**
     * @return array
     */
    public function dataProviderTestPostResultWithDifferentResult()
    {
        $postParams      = $this->getPostParamsVehicleSearch();

        return [
            [
                'postParams'   => $postParams,
                'searchResult' => $this->getVehicleSearchMultipleResult(),
                'expect'       => [
                    'status'     => self::HTTP_OK_CODE,
                    'instanceOf' => ViewModel::class,
                ],
            ],
            [
                'postParams'   => $postParams,
                'searchResult' => $this->getVehicleSearchOneResult(),
                'expect'       => [
                    'url' => VehicleUrlBuilderWeb::vehicle(self::$obfuscationMap[1234])
                        . '?type=' . ArrayUtils::tryGet($postParams, 'type') . '&backTo=search',
                ],
            ],
            [
                'postParams'   => $this->getPostParamsVehicleSearch(),
                'searchResult' => $this->getVehicleSearchNoResult(),
                'expect'       => [
                    'url' => VehicleUrlBuilderWeb::search() . '?type=' . ArrayUtils::tryGet($postParams, 'type'),
                ],
            ],
            [
                'postParams'   => null,
                'searchResult' => [],
                'expect'       => [
                    'url' => VehicleUrlBuilderWeb::search(),
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getPostParamsVehicleSearch()
    {
        return [
            'type'           => '0',
            'search-result'  => 'not-search',
            'vehicle-search' => 'Search',
        ];
    }

    /**
     * @return array
     */
    protected function getVehicleSearchNoResult()
    {
        return [
            "data" => [
                "resultCount"      => 0,
                "totalResultCount" => 0,
                "data"             => [],
                "searched"         => [
                    "format"        => "DATA_TABLES",
                    "search"        => "1HD1BDK10DY123456",
                    "searchFilter"  => "vin",
                    "registration"  => null,
                    "vin"           => "1HD1BDK10DY123456",
                    "sortDirection" => "ASC",
                    "rowCount"      => 10,
                    "start"         => 0,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getVehicleSearchOneResult()
    {
        return [
            "data" => [
                "resultCount"      => 1,
                "totalResultCount" => 1,
                "data"             => [
                    '1234' => [
                        'id'           => 1,
                        'vin'          => 'ABCDEFGH',
                        'registration' => 'FNZ 6JZ',
                        'make'         => 'Renault',
                        'model'        => 'Clio',
                        'displayDate'  => '10 Sep 2013 09:23',
                    ],
                ],
                "searched"         => [
                    "format"        => "DATA_TABLES",
                    "search"        => "1HD1BDK10DY123456",
                    "searchFilter"  => "vin",
                    "registration"  => null,
                    "vin"           => "1HD1BDK10DY123456",
                    "sortDirection" => "ASC",
                    "rowCount"      => 10,
                    "start"         => 0,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getVehicleSearchMultipleResult()
    {
        return [
            "data" => [
                "resultCount"      => 2,
                "totalResultCount" => 2,
                "data"             => [
                    [
                        'id'           => 1,
                        'vin'          => 'ABCDEFGH',
                        'registration' => 'FNZ 6JZ',
                        'make'         => 'Renault',
                        'model'        => 'Clio',
                        'displayDate'  => '10 Sep 2013 09:23',
                    ],
                    [
                        'id'           => 2,
                        'vin'          => 'ABCDEFGH',
                        'registration' => 'FNZ 6JZ',
                        'make'         => 'Renault',
                        'model'        => 'Clio',
                        'displayDate'  => '10 Sep 2013 09:23',
                    ],
                ],
                "searched"         => [
                    "format"        => "DATA_TABLES",
                    "search"        => "1HD1BDK10DY123456",
                    "searchFilter"  => "vin",
                    "registration"  => null,
                    "vin"           => "1HD1BDK10DY123456",
                    "sortDirection" => "ASC",
                    "rowCount"      => 10,
                    "start"         => 0,
                ],
            ],
        ];
    }

    /**
     * @param array $params
     * @param bool  $asDto
     *
     * @throws \Exception
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockVehicleMapper($params = [], $asDto = true)
    {
        $this->mockVehicleMapper = XMock::of(VehicleMapper::class);
        $this
            ->mockVehicleMapper
            ->method('getBydId')
            ->with($this->logicalOr(
                $this->equalTo(1),
                $this->equalTo(2),
                $this->equalTo(999),
                $this->equalTo(1234)
            ))
            ->will($this->returnCallback(
                function ($arg1) {
                    $v = new VehicleDto();
                    $v->setId($arg1);

                    return $v;
                }
            ));

        return $this->mockVehicleMapper;
    }

    /**
     * @throws \Exception
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockMapperFactory()
    {
        $map = [
            ['Vehicle', $this->getMockVehicleMapper()],
        ];

        $factoryMapper = XMock::of(MapperFactory::class);
        $factoryMapper->expects($this->any())
            ->method('__get')
            ->will($this->returnValueMap($map));

        return $factoryMapper;
    }

    /**
     * @param array $map
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createParamObfuscatorMock(array $map)
    {
        $toId = array_flip($map);

        $config         = $this->getServiceManager()->get('Config');
        $paramEncrypter = new ParamEncrypter(new EncryptionKey($config['security']['obfuscate']['key']));
        $paramEncoder   = new ParamEncoder();

        $mockParamObfuscator = $this->getMockBuilder(ParamObfuscator::class)
            ->setConstructorArgs([$paramEncrypter, $paramEncoder, $config])
            ->setMethods(['obfuscateEntry', 'deobfuscateEntry'])
            ->getMock();

        $this->mockMethod(
            $mockParamObfuscator, 'obfuscateEntry', null, function ($entryKey, $id) use ($map) {
                return $map[$id];
            }
        );

        $this->mockMethod(
            $mockParamObfuscator, 'deobfuscateEntry', null, function ($entryKey, $obfuscatedId) use ($toId) {
                return $toId[$obfuscatedId];
            }
        );

        return $mockParamObfuscator;
    }
}
