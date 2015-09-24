<?php

namespace DvsaCommonTest\UrlBuilder;

use DvsaCommon\UrlBuilder\AbstractUrlBuilder;
use DvsaCommon\UrlBuilder\VehicleTestingStationUrlBuilderWeb;

class VehicleTestingStationUrlBuilderWebTest extends \PHPUnit_Framework_TestCase
{
    const VTS_ID = 999999;
    const VTS_NUMBER = 'V1326789';

    public function test()
    {
        $base = '/vehicle-testing-station';
        $this->checkUrl(VehicleTestingStationUrlBuilderWeb::create(), $base . '/create');
        $this->checkUrl(VehicleTestingStationUrlBuilderWeb::createConfirm(), $base . '/create/confirmation');

        $base = $base . '/' . self::VTS_ID;
        $this->checkUrl(VehicleTestingStationUrlBuilderWeb::byId(self::VTS_ID), $base);
        $this->checkUrl(VehicleTestingStationUrlBuilderWeb::edit(self::VTS_ID), $base . '/edit');
        $this->checkUrl(VehicleTestingStationUrlBuilderWeb::contactDetails(self::VTS_ID), $base . '/contact-details');

        $this->checkUrl(
            VehicleTestingStationUrlBuilderWeb::siteDetails(self::VTS_ID),
            $base . '/site-details'
        );
        $this->checkUrl(
            VehicleTestingStationUrlBuilderWeb::siteDetailsConfirm(self::VTS_ID),
            $base . '/site-details/confirmation'
        );

        $this->checkUrl(
            VehicleTestingStationUrlBuilderWeb::testingFacilities(self::VTS_ID),
            $base . '/testing-facilities'
        );
        $this->checkUrl(
            VehicleTestingStationUrlBuilderWeb::testingFacilitiesConfirmation(self::VTS_ID),
            $base . '/testing-facilities/confirmation'
        );


    }

    private function checkUrl(AbstractUrlBuilder $urlBuilder, $expectUrl)
    {
        $this->assertEquals($expectUrl, $urlBuilder->toString());
        $this->assertInstanceOf(VehicleTestingStationUrlBuilderWeb::class, $urlBuilder);
    }
}
