<?php

namespace DvsaCommonTest\UrlBuilder;

use DvsaCommon\UrlBuilder\TesterUrlBuilder;
use PHPUnit_Framework_TestCase;

/**
 * Tests for ...
 */
class TesterUrlBuilderTest extends PHPUnit_Framework_TestCase
{
    public function testTesterUrlBuilder()
    {
        $urlBuilder = new TesterUrlBuilder();

        $this->assertInstanceOf(TesterUrlBuilder::class, $urlBuilder);

        $this->assertEquals('tester', $urlBuilder->create()->toString());
        $this->assertEquals('tester/full', $urlBuilder->create()->testerFull()->toString());
        $this->assertEquals('tester/2/mot-test-log', $urlBuilder->create()->motTestLog(2)->toString());
        $this->assertEquals('tester/2/mot-test-log/summary', $urlBuilder->create()->motTestLogSummary(2)->toString());
        $this->assertEquals('tester/2/in-progress-test-id', $urlBuilder->create()->testerInProgressTestNumber(2));
        $this->assertEquals('tester/2/vehicle-testing-stations', $urlBuilder->create()->vehicleTestingStations(2));
        $this->assertEquals(
            'tester/2/vts-slot-balance',
            $urlBuilder->create()->vehicleTestingStationWithSlotBalance(2)
        );
    }
}
