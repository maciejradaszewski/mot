<?php

namespace DvsaCommon\UrlBuilder;

/**
 * URL builder for vehicle test resources
 */
class VehicleTestUrlBuilder extends AbstractUrlBuilder
{
    const ENFORCEMENT_TEST_SUMMARY = '/enforcement/mot-test/:id/test-summary';

    protected $routesStructure =[
        self::ENFORCEMENT_TEST_SUMMARY => ''
    ];

    public function testSummary($testId)
    {
        return $this->appendRoutesAndParams(self::ENFORCEMENT_TEST_SUMMARY)->routeParam('id', $testId);
    }
}
