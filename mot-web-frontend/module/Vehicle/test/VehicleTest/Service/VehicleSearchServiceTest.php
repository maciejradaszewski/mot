<?php
namespace VehicleTest\Service;

use DvsaClient\MapperFactory;
use DvsaCommon\Obfuscate\EncryptionKey;
use DvsaCommon\Obfuscate\ParamEncoder;
use DvsaCommon\Obfuscate\ParamEncrypter;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\TestUtils\XMock;
use DvsaMotTestTest\Controller\AbstractDvsaMotTestTestCase;
use Vehicle\Controller\VehicleController;
use Vehicle\Service\VehicleSearchService;

/**
 * Class VehicleSearchServiceTest.
 */
class VehicleSearchServiceTest extends AbstractDvsaMotTestTestCase
{
    /* @var \Vehicle\Service\VehicleSearchService */
    protected $vehicleSearch;
    protected $restClientMock;
    protected $postData;

    protected function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $serviceManager->setAllowOverride(true);
        $this->setServiceManager($serviceManager);

        $this->controller = new VehicleController($this->createParamObfuscator(), XMock::of(MapperFactory::class));
        $this->controller->setServiceLocator(Bootstrap::getServiceManager());

        $this->restClientMock = $this->getRestClientMockForServiceManager();

        $this->vehicleSearch = new VehicleSearchService(
            $this->controller, $this->restClientMock, $this->postData, $this->createParamObfuscator()
        );

        parent::setUp();
    }

    public function testGetVehicleResultsOneResult()
    {
        $vehicleResults                 = $this->getVehicleResults();
        $vehicleResults['data']['data'] = $this->getVehicleSearchOneResult();

        $this->mockMethod($this->restClientMock, 'getWithParams', $this->at(0), $vehicleResults);

        $this->vehicleSearch->getVehicleResults();
    }

    public function testGetVehicleResultsMultiResult()
    {
        $vehicleResults                 = $this->getVehicleResults();
        $vehicleResults['data']['data'] = $this->getVehicleSearchMultiResult();

        $this->mockMethod($this->restClientMock, 'getWithParams', $this->at(0), $vehicleResults);

        $this->vehicleSearch->getVehicleResults();
    }

    public function testGetVehicleResultsNoResult()
    {
        $vehicleResults                             = $this->getVehicleResults();
        $vehicleResults['data']['totalResultCount'] = 0;

        $this->mockMethod($this->restClientMock, 'getWithParams', $this->at(0), $vehicleResults);

        $this->vehicleSearch->getVehicleResults();
    }

    public function testCheckVehicleResultsOneResult()
    {
        $vehicleResults                             = $this->getVehicleResults();
        $vehicleResults['data']['data']             = $this->getVehicleSearchOneResult();
        $vehicleResults['data']['totalResultCount'] = 1;

        $this->mockMethod($this->restClientMock, 'getWithParams', $this->at(0), $vehicleResults);

        $this->vehicleSearch->getVehicleResults();

        $this->assertInstanceOf(\Zend\View\Model\ViewModel::class, $this->vehicleSearch->checkVehicleResults());
    }

    public function testCheckVehicleResultsMultiResult()
    {
        $vehicleResults                 = $this->getVehicleResults();
        $vehicleResults['data']['data'] = $this->getVehicleSearchMultiResult();

        $this->mockMethod($this->restClientMock, 'getWithParams', $this->at(0), $vehicleResults);

        $this->vehicleSearch->getVehicleResults();

        $this->assertInstanceOf(\Zend\View\Model\ViewModel::class, $this->vehicleSearch->checkVehicleResults());
    }

    public function testCheckVehicleResultsNoResult()
    {
        $vehicleResults                             = $this->getVehicleResults();
        $vehicleResults['data']['totalResultCount'] = 0;

        $this->mockMethod($this->restClientMock, 'getWithParams', $this->at(0), $vehicleResults);

        $this->vehicleSearch->getVehicleResults();
        $this->event->setResponse(new \Zend\Http\Response());
        $this->assertInstanceOf(\Zend\Http\Response::class, $this->vehicleSearch->checkVehicleResults());
    }

    protected function getVehicleResults()
    {
        return [
            "data" => [
                "resultCount"      => 4,
                "totalResultCount" => 4,
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

    protected function getVehicleSearchOneResult()
    {
        return [
            "26" => [
                "vin"          => "1HD1BDK10DY123456",
                "registration" => "SSE24MAR",
                "make"         => "Harley Davidson",
                "model"        => "Service Car Trike",
                "displayDate"  => "2014-10-07 12:32:45",
            ],
        ];
    }

    protected function getVehicleSearchMultiResult()
    {
        return [
            "26" => [
                "vin"          => "1HD1BDK10DY123456",
                "registration" => "SSE24MAR",
                "make"         => "Harley Davidson",
                "model"        => "Service Car Trike",
                "displayDate"  => "2014-10-07 12:32:45",
            ],
            "27" => [
                "vin"          => "1HD1BDK10DY123456",
                "registration" => "SSE24MAR",
                "make"         => "Harley Davidson",
                "model"        => "Service Car Trike",
                "displayDate"  => "2014-10-07 12:32:45",
            ],
            "28" => [
                "vin"          => "1HD1BDK10DY123456",
                "registration" => "SSE24MAR",
                "make"         => "Harley Davidson",
                "model"        => "Service Car Trike",
                "displayDate"  => "2014-10-07 12:32:45",
            ],
            "29" => [
                "vin"          => "1HD1BDK10DY123456",
                "registration" => "SSE24MAR",
                "make"         => "Harley Davidson",
                "model"        => "Service Car Trike",
                "displayDate"  => "2014-10-07 12:32:45",
            ],
        ];
    }

    protected function getPost()
    {
        return [
            'search'            => 'fnz',
            'type'              => 'registration',
            'format'            => 'DATA_TABLES',
        ];
    }

    /**
     * @return ParamObfuscator
     */
    protected function createParamObfuscator()
    {
        $config         = $this->getServiceManager()->get('Config');
        $paramEncrypter = new ParamEncrypter(new EncryptionKey($config['security']['obfuscate']['key']));
        $paramEncoder   = new ParamEncoder();

        return new ParamObfuscator($paramEncrypter, $paramEncoder, $config);
    }
}
