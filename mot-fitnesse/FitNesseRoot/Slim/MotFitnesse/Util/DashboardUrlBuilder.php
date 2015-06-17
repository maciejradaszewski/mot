<?php

namespace MotFitnesse\Util;

/**
 * urls for dashboard
 */
class DashboardUrlBuilder extends UrlBuilder
{
    const DASHBOARD = '/person/:personId/dashboard';
    const USER_STATS = '/person/:personId/stats';

    protected $routesStructure
        = [
            self::DASHBOARD => '',
            self::USER_STATS  => '',
        ];

    public static function dashboard($id)
    {
        return (new self())->appendRoutesAndParams(self::DASHBOARD)->routeParam('personId', $id);
    }

    public static function userStats($id)
    {
        return (new self())->appendRoutesAndParams(self::USER_STATS)->routeParam('personId', $id);
    }
}
