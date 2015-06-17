<?php

namespace DvsaCommon\UrlBuilder;

/**
 * Urls for tester resources.
 */
class TesterUrlBuilder extends UrlBuilder
{
    const TESTER                     = 'tester[/:id]';
    const TESTER_FULL                = '/full';
    const TESTER_IN_PROGRESS_TEST_ID = '/in-progress-test-id';
    const VEHICLE_TESTING_STATIONS   = '/vehicle-testing-stations';
    const VTS_SLOT_BALANCE   = '/vts-slot-balance';

    /**
     * @var array
     */
    protected $routesStructure
        = [
            self::TESTER => [
                self::TESTER_FULL                => '',
                self::TESTER_IN_PROGRESS_TEST_ID => '',
                self::VEHICLE_TESTING_STATIONS   => '',
                self::VTS_SLOT_BALANCE           => '',
            ],
        ];

    public function testerFull()
    {
        return $this->appendRoutesAndParams(self::TESTER_FULL);
    }

    /**
     * @param int $personId
     *
     * @return $this
     */
    public function testerInProgressTestNumber($personId)
    {
        return $this
            ->routeParam('id', $personId)
            ->appendRoutesAndParams(self::TESTER_IN_PROGRESS_TEST_ID);
    }

    /**
     * @param int $testerId
     *
     * @return $this
     */
    public function vehicleTestingStations($testerId)
    {
        return $this
            ->routeParam('id', $testerId)
            ->appendRoutesAndParams(self::VEHICLE_TESTING_STATIONS);
    }

    /**
     * @param int $testerId
     *
     * @return $this
     */
    public function vehicleTestingStationWithSlotBalance($testerId)
    {
        return $this
            ->routeParam('id', $testerId)
            ->appendRoutesAndParams(self::VTS_SLOT_BALANCE);
    }

    /**
     * @return TesterUrlBuilder
     */
    public static function create()
    {
        return (new TesterUrlBuilder())
            ->appendRoutesAndParams(self::TESTER);
    }
}
