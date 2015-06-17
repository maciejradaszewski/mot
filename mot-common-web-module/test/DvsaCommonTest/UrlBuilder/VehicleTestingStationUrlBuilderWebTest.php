<?php

namespace DvsaCommonTest\UrlBuilder;

use DvsaCommon\UrlBuilder\VehicleTestingStationUrlBuilderWeb;

class VehicleTestingStationUrlBuilderWebTest extends \PHPUnit_Framework_TestCase
{
    const VTS_ID = 999999;
    const VTS_NUMBER = 'V1326789';

    public function test()
    {
        $urlMainPart = '/vehicle-testing-station';

        $urlBuilder = new VehicleTestingStationUrlBuilderWeb();

        $this->assertInstanceOf(VehicleTestingStationUrlBuilderWeb::class, $urlBuilder);

        $this->assertEquals(
            $urlMainPart . '/' . self::VTS_ID,
            $urlBuilder::byId(self::VTS_ID)->toString()
        );
        $this->assertEquals(
            $urlMainPart . '/site/' . self::VTS_NUMBER,
            $urlBuilder::bySiteNumber(self::VTS_NUMBER)->toString()
        );
        $this->assertEquals(
            $urlMainPart . '/' . self::VTS_ID . '/edit',
            $urlBuilder::edit(self::VTS_ID)->toString()
        );
        $this->assertEquals(
            $urlMainPart . '/' . self::VTS_ID . '/contact-details',
            $urlBuilder::contactDetails(self::VTS_ID)->toString()
        );
    }
}
