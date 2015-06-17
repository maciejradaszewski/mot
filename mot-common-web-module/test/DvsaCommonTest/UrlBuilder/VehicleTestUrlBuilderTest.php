<?php

namespace DvsaCommonTest\UrlBuilder;

use DvsaCommon\UrlBuilder\VehicleTestUrlBuilder;
use PHPUnit_Framework_TestCase;

/**
 * To unit test VehicleTestUrlBuilder
 */
class VehicleTestUrlBuilderTest extends PHPUnit_Framework_TestCase
{

    public function test_testerInProgressTestId()
    {
        $testId = 12;
        $expectedUrl = sprintf('/enforcement/mot-test/%d/test-summary', $testId);

        $buildUrl = VehicleTestUrlBuilder::create()->testSummary($testId);

        $this->assertSame($expectedUrl, $buildUrl->toString());
    }

}
