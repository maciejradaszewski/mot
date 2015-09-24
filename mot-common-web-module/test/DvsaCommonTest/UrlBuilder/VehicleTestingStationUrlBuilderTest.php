<?php

namespace DvsaCommonTest\UrlBuilder;

use DvsaCommon\UrlBuilder\VehicleTestingStationUrlBuilder;

class VehicleTestingStationUrlBuilderTest extends \PHPUnit_Framework_TestCase
{
    const VTS_ID = 999999;
    const VTS_NUMBER = 'V1326789';
    const CONTACT_ID = 888888;

    public function test()
    {
        $urlBuilder = new VehicleTestingStationUrlBuilder();

        $this->assertInstanceOf(VehicleTestingStationUrlBuilder::class, $urlBuilder);

        $this->assertEquals(
            'vehicle-testing-station-application-start/8888',
            $urlBuilder::vehicleTestingStationApplicationStart()->routeParam('designatedManagerId', 8888)->toString()
        );

        //  --  application routes  --
        $urlAppPart = 'vehicle-testing-station-application/8888';

        $this->assertEquals(
            $urlAppPart,
            $urlBuilder::vehicleTestingStationApplication()->routeParam('uuid', 8888)->toString()
        );

        $this->assertEquals(
            $urlAppPart . '/application-summary',
            $urlBuilder::vehicleTestingStationApplication()->routeParam('uuid', 8888)->applicationSummary()->toString()
        );

        $this->assertEquals(
            $urlAppPart . '/applicant-details',
            $urlBuilder::vehicleTestingStationApplication()->routeParam('uuid', 8888)->applicantDetails()->toString()
        );

        $this->assertEquals(
            $urlAppPart . '/evidence-of-use',
            $urlBuilder::vehicleTestingStationApplication()->routeParam('uuid', 8888)->evidenceOfUse()->toString()
        );

        $this->assertEquals(
            $urlAppPart . '/planning-permission',
            $urlBuilder::vehicleTestingStationApplication()->routeParam('uuid', 8888)->planningPermission()->toString()
        );

        $this->assertEquals(
            $urlAppPart . '/plans-and-dimensions',
            $urlBuilder::vehicleTestingStationApplication()->routeParam('uuid', 8888)->plansAndDimensions()->toString()
        );

        $this->assertEquals(
            $urlAppPart . '/testing-facilities',
            $urlBuilder::vehicleTestingStationApplication()->routeParam('uuid', 8888)->testingFacilities()->toString()
        );

        $this->assertEquals(
            $urlAppPart . '/vehicle-testing-station-details',
            $urlBuilder::vehicleTestingStationApplication()
                ->routeParam('uuid', 8888)->vehicleTestingStationDetails()->toString()
        );

        $this->assertEquals(
            $urlAppPart . '/documents',
            $urlBuilder::vehicleTestingStationApplication()->routeParam('uuid', 8888)->documents()->toString()
        );

        $this->assertEquals(
            $urlAppPart . '/status',
            $urlBuilder::vehicleTestingStationApplication()->routeParam('uuid', 8888)->status()->toString()
        );

        //  --  vts && site routes  --
        $this->assertEquals(
            'vehicle-testing-station/search',
            $urlBuilder::search()->toString()
        );

        $urlVtsPart = 'vehicle-testing-station';
        $this->assertEquals(
            $urlVtsPart . '/' . self::VTS_ID,
            $urlBuilder::vtsById(self::VTS_ID)->toString()
        );
        $this->assertEquals(
            $urlVtsPart . '/' . self::VTS_ID . '/test-in-progress',
            $urlBuilder::testInProgress(self::VTS_ID)->toString()
        );
        $this->assertEquals(
            $urlVtsPart . '/' . self::VTS_ID . '/default-brake-tests',
            $urlBuilder::defaultBrakeTests(self::VTS_ID)->toString()
        );
        $this->assertEquals(
            $urlVtsPart . '/' . self::VTS_ID . '/contact/' . self::CONTACT_ID,
            $urlBuilder::contact(self::VTS_ID, self::CONTACT_ID)->toString()
        );
        $this->assertEquals(
            $urlVtsPart . '/' . self::VTS_ID . '/contact/' . self::CONTACT_ID .'/update',
            $urlBuilder::contactUpdate(self::VTS_ID, self::CONTACT_ID)->toString()
        );
    }
}
