<?php

namespace MotFitnesse\Util;

/**
 * Class VehicleUrlBuilder
 *
 * @package MotFitnesse\Util
 */
class VehicleUrlBuilder extends AbstractUrlBuilder
{
    const VEHICLE = '/vehicle[/:id]';
    const VEHICLE_DVLA = '/vehicle-dvla/:id';

    const TEST_IN_PROGRESS_CHECK = '/test-in-progress-check';
    const TEST_EXPIRY_CHECK = '/test-expiry-check[/:isDvla]';
    const TEST_HISTORY = '/test-history';
    const RETEST_ELIGIBILITY_CHECK = '/retest-eligibility-check/[:siteId]';

    protected $routesStructure
        = [
            self::VEHICLE => [
                self::TEST_IN_PROGRESS_CHECK           => '',
                self::TEST_EXPIRY_CHECK =>'',
                self::TEST_HISTORY => '',
                self::RETEST_ELIGIBILITY_CHECK => '',
            ],
            self::VEHICLE_DVLA => '',
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

    public function testExpiryCheck()
    {
        return $this->appendRoutesAndParams(self::TEST_EXPIRY_CHECK);
    }

    public function testHistory()
    {
        return $this->appendRoutesAndParams(self::TEST_HISTORY);
    }

    public function retestEligibilityCheck($siteId)
    {
        $url = $this->appendRoutesAndParams(self::RETEST_ELIGIBILITY_CHECK);

        if ($siteId !== null) {
            $url->routeParam('siteId', $siteId);
        }

        return $url;
    }
}
