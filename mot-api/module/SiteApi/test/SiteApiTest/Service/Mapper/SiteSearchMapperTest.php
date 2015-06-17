<?php

namespace SiteApiTest\Service\Mapper;

use DvsaCommon\Dto\Site\SiteSearchDto;
use SiteApi\Service\Mapper\SiteSearchMapper;

/**
 * Class SiteSearchMapperTest
 * @package SiteApiTest\Service\Mapper
 */
class SiteSearchMapperTest extends \PHPUnit_Framework_TestCase
{
    const SITE_ID = 1;
    const SITE_NUMBER = 'V1234';
    const SITE_NAME = 'Name';
    const SITE_ROLES = '1, 2, 4';
    const SITE_TOWN = 'Toulouse';
    const SITE_POSTCODE = 'AAA ZZZ';

    public function testMapper()
    {
        $mapper = new SiteSearchMapper();
        $site = [
            'id' => self::SITE_ID,
            'site_number' => self::SITE_NUMBER,
            'name' => self::SITE_NAME,
            'roles' => self::SITE_ROLES,
            'town' => self::SITE_TOWN,
            'postcode' => self::SITE_POSTCODE,
        ];

        $dto = $mapper->toDto($site);
        $this->assertInstanceOf(SiteSearchDto::class, $dto);
        $this->assertSame(self::SITE_ID, $dto->getId());
        $this->assertSame(self::SITE_NUMBER, $dto->getSiteNumber());
        $this->assertSame(self::SITE_NAME, $dto->getSiteName());
        $this->assertSame(self::SITE_ROLES, $dto->getSiteVehicleClass());
        $this->assertSame(self::SITE_TOWN, $dto->getSiteTown());
        $this->assertSame(self::SITE_POSTCODE, $dto->getSitePostcode());

        $dto = $mapper->manyToDto([$site]);
        $this->assertInstanceOf(SiteSearchDto::class, $dto[0]);
    }
}
