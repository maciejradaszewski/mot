<?php

namespace DvsaCommon\UrlBuilder;

class QualificationDetailsUrlBuilder extends AbstractUrlBuilder
{
    const DEMO_TEST_REQUEST = 'demo-test-requests';

    protected $routesStructure = [
        self::DEMO_TEST_REQUEST => '',
    ];

    /**
     * @return $this
     */
    public static function demoTestRequests()
    {
        return self::of()->appendRoutesAndParams(self::DEMO_TEST_REQUEST);
    }
}
