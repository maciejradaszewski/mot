<?php

namespace DvsaClientTest\Mapper;

use DvsaClient\Entity\VehicleTestingStation;
use DvsaClient\Mapper\VehicleTestingStationMapper;
use DvsaCommon\Dto\Site\SiteListDto;
use DvsaCommon\UrlBuilder\VehicleTestingStationUrlBuilder;

/**
 * Class VehicleTestingStationMapperTest
 *
 * @package DvsaClientTest\Mapper
 */
class VehicleTestingStationMapperTest extends AbstractMapperTest
{
    const ORGANISATION_ID = 1;

    /** @var $mapper VehicleTestingStationMapper */
    private $mapper;

    public function setUp()
    {
        parent::setUp();

        $this->mapper = new VehicleTestingStationMapper($this->client);
    }

    public function testFetchAllForOrganisation()
    {
        $this->client->expects($this->once())
            ->method('get')
            ->willReturn(['data' => ['vehicleTestingStation' => $this->getSiteAsArray()]]);
        $this->assertInstanceOf(
            VehicleTestingStation::class,
            $this->mapper->fetchAllForOrganisation(self::ORGANISATION_ID)[0]
        );
    }

    public function testGetById()
    {
        $this->client->expects($this->once())
            ->method('get')
            ->willReturn(['data' => ['vehicleTestingStation' => $this->getSiteAsArray()]]);

        $this->assertSame(
            'Site1',
            $this->mapper->getById(self::ORGANISATION_ID)['name']
        );
    }

    public function testGetBySiteNumber()
    {
        $this->client->expects($this->once())
            ->method('get')
            ->willReturn(['data' => ['vehicleTestingStation' => $this->getSiteAsArray()]]);

        $this->assertSame(
            'Site1',
            $this->mapper->getBySiteNumber(self::ORGANISATION_ID)['name']
        );
    }

    public function testCreate()
    {
        $this->client->expects($this->once())
            ->method('postJson')
            ->willReturn(['data' => ['id' => self::ORGANISATION_ID]]);

        $this->assertSame(
            self::ORGANISATION_ID,
            $this->mapper->create([])
        );
    }

    public function testUpdate()
    {
        $this->client->expects($this->once())
            ->method('putJson')
            ->willReturn(['data' => ['id' => self::ORGANISATION_ID]]);

        $this->assertSame(
            self::ORGANISATION_ID,
            $this->mapper->update(self::ORGANISATION_ID, [])
        );
    }

    public function testSaveDefaultBrakeTests()
    {
        $this->client->expects($this->once())
            ->method('putJson')
            ->willReturn(['data' => ['id' => self::ORGANISATION_ID]]);

        $this->assertNull(
            $this->mapper->saveDefaultBrakeTests(self::ORGANISATION_ID, [])
        );
    }

    public function getSiteAsArray()
    {
        return [
            'name' => 'Site1',
            'address' => [
                'town' => 'Toulouse',
            ],
            'contacts' => [],
            'positions' => [
                [
                    'person' => [],
                    'role' => 1,
                    'status' => 1,
                    'actionedOn' => 1,
                    'id' => 1,
                ]
            ],
            'siteTestingDailySchedule' => [
                [
                    'weekday' => '',
                    'openTime' => null,
                    'closeTime' => null,
                ]
            ],
        ];
    }

    public function testSearch()
    {
        $this->client->expects($this->once())
            ->method('post')
            ->with(VehicleTestingStationUrlBuilder::search(), [])
            ->willReturn(['data' => ['_class' => SiteListDto::class]]);

        $this->assertInstanceOf(
            SiteListDto::class,
            $this->mapper->search([])
        );
    }
}
