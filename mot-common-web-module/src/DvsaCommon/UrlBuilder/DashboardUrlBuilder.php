<?php

namespace DvsaCommon\UrlBuilder;

/**
 * urls for dashboard
 */
class DashboardUrlBuilder extends UrlBuilder
{
    const DASHBOARD = 'person/:personId/dashboard';
    const USER_STATS = 'person/:personId/stats';
    const CONTINGENCY_TESTS = 'contingency';

    protected $routesStructure
        = [
            self::DASHBOARD => '',
            self::USER_STATS  => '',
            self::CONTINGENCY_TESTS  => '',
        ];

    public static function dashboard($id)
    {
        return (new self())->appendRoutesAndParams(self::DASHBOARD)->routeParam('personId', $id);
    }

    public static function userStats($id)
    {
        return (new self())->appendRoutesAndParams(self::USER_STATS)->routeParam('personId', $id);
    }

    public static function contingencyTests()
    {
        return (new self())->appendRoutesAndParams(self::CONTINGENCY_TESTS);
    }
}
