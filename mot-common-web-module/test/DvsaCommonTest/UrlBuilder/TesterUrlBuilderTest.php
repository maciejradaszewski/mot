<?php

namespace DvsaCommonTest\UrlBuilder;

use DvsaCommon\UrlBuilder\TesterUrlBuilder;
use PHPUnit_Framework_TestCase;

/**
 * Tests for ...
 */
class TesterUrlBuilderTest extends PHPUnit_Framework_TestCase
{
    public function test_testerInProgressTestId()
    {
        $testerInProgressTestBuilder = TesterUrlBuilder::create()->testerInProgressTestNumber(13);

        $this->assertSame('tester/13/in-progress-test-id', $testerInProgressTestBuilder->toString());
    }
}
