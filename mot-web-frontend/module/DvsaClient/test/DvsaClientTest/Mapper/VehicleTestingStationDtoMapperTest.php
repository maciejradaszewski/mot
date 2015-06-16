<?php

namespace DvsaClientTest\Mapper;

use DvsaClient\Mapper\VehicleTestingStationDtoMapper;
use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Site\SiteContactDto;
use DvsaCommon\Dto\Site\VehicleTestingStationDto;
use DvsaCommon\Enum\SiteContactTypeCode;
use DvsaCommon\UrlBuilder\VehicleTestingStationUrlBuilder;
use DvsaCommon\Utility\DtoHydrator;

/**
 * Test Mapper to get VTS data as dto objects from API
 *
 * @package DvsaClientTest\Mapper
 */
class VehicleTestingStationDtoMapperTest extends AbstractMapperTest
{
    const ID = 99999;
    const SITE_NR = 'V12345';

    /** @var VehicleTestingStationDtoMapper $mapper */
    private $mapper;

    public function setUp()
    {
        parent::setUp();

        $this->mapper = new VehicleTestingStationDtoMapper($this->client);
    }

    public function testGetById()
    {
        $expectDto = self::getVtsDto();

        $this->setupClientMockGet(
            VehicleTestingStationUrlBuilder::vtsById(self::ID)->queryParam('dto', true),
            ['data' => DtoHydrator::dtoToJson($expectDto)]
        );
        $actualDto = $this->mapper->getById(self::ID);

        $this->assertEquals($expectDto, $actualDto);
    }

    public function testGetBySiteNumber()
    {
        $expectDto = self::getVtsDto();

        $this->setupClientMockGet(
            VehicleTestingStationUrlBuilder::vtsBySiteNr(self::SITE_NR)->queryParam('dto', true),
            ['data' => DtoHydrator::dtoToJson($expectDto)]
        );

        $actualDto = $this->mapper->getBySiteNumber(self::SITE_NR);

        $this->assertEquals($expectDto, $actualDto);
    }

    public function testUpdateContact()
    {
        $contactDto = new SiteContactDto();
        $contactDto
            ->setType(SiteContactTypeCode::CORRESPONDENCE)
            ->setAddress(new AddressDto());

        $apiUrl = VehicleTestingStationUrlBuilder::contactUpdate(self::ID);
        $jsonContact = DtoHydrator::dtoToJson($contactDto);

        $this->setupClientMockPut($apiUrl, $jsonContact, ['id' => self::ID]);

        $this->mapper->updateContactDetails(self::ID, $contactDto);
    }

    private static function getVtsDto()
    {
        return (new VehicleTestingStationDto())
            ->setId(self::ID)
            ->setSiteNumber(self::SITE_NR)
            ->setName('UnitTest');
    }
}
