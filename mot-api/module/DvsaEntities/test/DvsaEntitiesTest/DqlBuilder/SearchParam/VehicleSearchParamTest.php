<?php
namespace DvsaEntitiesTest\DqlBuilder\SearchParam;

use DoctrineORMModule\Options\EntityManager;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\DqlBuilder\SearchParam\VehicleSearchParam;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;

/**
 * Class VehicleSearchParamTest
 *
 * @package DvsaEntities\DqlBuilder\SearchParam
 */
class VehicleSearchParamTest extends AbstractServiceTestCase
{
    const SEARCH_TEST   = 'FNZ6110';
    const TYPE_VIN_TEST = 'vin';
    const TYPE_VRM_TEST = 'registration';
    const TYPE_INVALID_TEST = 'INVALID';

    protected $mockEm;

    public function setup()
    {
        $this->mockEm = XMock::of(EntityManager::class, ['getRepository']);
    }

    /**
     * Test we can create a VehicleSearchParam by vrm
     */
    public function testVehicleSearchParamVrm()
    {
        $vehicleSearchParam = new VehicleSearchParam($this->mockEm, self::SEARCH_TEST, self::TYPE_VRM_TEST);
        $vehicleSearchParam->process();

        $this->assertSame(self::SEARCH_TEST, $vehicleSearchParam->getSearch());
        $this->assertSame(self::TYPE_VRM_TEST, $vehicleSearchParam->getSearchType());
        $this->assertSame('FNZ6110', $vehicleSearchParam->getRegistration());
    }

    /**
     * Test we can create a VehicleSearchParam by vrm
     */
    public function testVehicleSearchParamVin()
    {
        $vehicleSearchParam = new VehicleSearchParam($this->mockEm, self::SEARCH_TEST, self::TYPE_VIN_TEST);
        $vehicleSearchParam->process();

        $this->assertSame(self::SEARCH_TEST, $vehicleSearchParam->getSearch());
        $this->assertSame(self::TYPE_VIN_TEST, $vehicleSearchParam->getSearchType());
        $this->assertSame('FNZ6110', $vehicleSearchParam->getVin());
    }

    /**
     * Test that an invalid type throw an exception
     */
    public function testVehicleSearchParamInvalidTypeThrowException()
    {
        $this->setExpectedException('UnexpectedValueException');

        $vehicleSearchParam = new VehicleSearchParam($this->mockEm, self::SEARCH_TEST, self::TYPE_INVALID_TEST);
        $vehicleSearchParam->process();
    }

    /**
     * Test that an invalid search throw an exception
     */
    public function testVehicleSearchParamInvalidSearchThrowException()
    {
        $this->setExpectedException('UnexpectedValueException');

        $vehicleSearchParam = new VehicleSearchParam($this->mockEm, '', self::TYPE_VIN_TEST);
        $vehicleSearchParam->process();
    }

    /**
     * Test we can create a VehicleSearchParam and the toArray function
     */
    public function testVehicleSearchParamToArray()
    {
        $vehicleSearchParam = new VehicleSearchParam($this->mockEm, self::SEARCH_TEST, self::TYPE_VIN_TEST);
        $vehicleSearchParam->process();

        $this->assertSame($this->getToArray(), $vehicleSearchParam->toArray());
    }

    protected function getToArray()
    {
        return [
            'format' => 'DATA_OBJECT',
            'search' => 'FNZ6110',
            'searchType' => 'vin',
            'registration' => null,
            'vin' => 'FNZ6110',
            'sortDirection' => null,
            'rowCount' => 10,
            'start' => 0,
        ];
    }
}
