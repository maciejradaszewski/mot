<?php

namespace DvsaCommon\UrlBuilder;

/**
 * Class VehicleUrlBuilder
 *
 * @package DvsaCommon\UrlBuilder
 */
class VehicleUrlBuilder extends AbstractUrlBuilder
{
    const VEHICLE = 'vehicle[/:id]';
    const VEHICLE_LIST = 'vehicle/list';
    const VEHICLE_DVLA = 'vehicle-dvla/:id';

    const TEST_IN_PROGRESS_CHECK = '/test-in-progress-check';
    const TEST_EXPIRY_CHECK = '/test-expiry-check[/:isDvla]';
    const TEST_HISTORY = '/test-history';
    const RETEST_ELIGIBILITY_CHECK = '/retest-eligibility-check/[:siteId]';

    const SEARCH = 'vehicle-search';
    const DEMO_SEARCH = 'demo-vehicle-search';

    protected $routesStructure
        = [
            self::VEHICLE      => [
                self::TEST_IN_PROGRESS_CHECK   => '',
                self::TEST_EXPIRY_CHECK        => '',
                self::TEST_HISTORY             => '',
                self::RETEST_ELIGIBILITY_CHECK => '',
            ],
            self::VEHICLE_DVLA => '',
            self::SEARCH       => '',
            self::DEMO_SEARCH  => '',
            self::VEHICLE_LIST => ''
        ];

    /**
     * @return VehicleUrlBuilder
     */
    public static function vehicle($id = null)
    {
        $url = self::of()->appendRoutesAndParams(self::VEHICLE);

        if ($id !== null) {
            $url->routeParam('id', $id);
        }

        return $url;
    }

    public static function vehicleList()
    {
        return self::of()->appendRoutesAndParams(self::VEHICLE_LIST);
    }

    /**
     * @return VehicleUrlBuilder
     */
    public static function dvlaVehicle($id = null)
    {
        $url = self::of()->appendRoutesAndParams(self::VEHICLE_DVLA);

        if ($id !== null) {
            $url->routeParam('id', $id);
        }

        return $url;
    }

    public function testInProgressCheck()
    {
        return $this->appendRoutesAndParams(self::TEST_IN_PROGRESS_CHECK);
    }

    /**
     * @param  bool $isDvlaVehicle
     * @return $this
     */
    public static function testExpiryCheck($vehicleId, $isDvlaVehicle)
    {
        return self::vehicle($vehicleId)
            ->appendRoutesAndParams(self::TEST_EXPIRY_CHECK)
            ->routeParam('isDvla', (int) $isDvlaVehicle);
    }

    public static function testHistory($vehicleId)
    {
        return self::vehicle($vehicleId)
            ->appendRoutesAndParams(self::TEST_HISTORY);
    }

    public static function retestEligibilityCheck($vehicleId, $siteId)
    {
        return self::vehicle($vehicleId)
            ->appendRoutesAndParams(self::RETEST_ELIGIBILITY_CHECK)
            ->routeParam('siteId', $siteId);
    }

    public static function search()
    {
        return self::of()->appendRoutesAndParams(self::SEARCH);
    }

    public static function demoSearch()
    {
        return self::of()->appendRoutesAndParams(self::DEMO_SEARCH);
    }
}
