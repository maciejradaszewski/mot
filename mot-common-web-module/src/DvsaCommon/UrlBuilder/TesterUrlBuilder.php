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
    const VTS_SLOT_BALANCE           = '/vts-slot-balance';
    const TESTER_TEST_LOG            = '/mot-test-log';
    const TESTER_TEST_LOG_SUMMARY    = '/summary';

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
                self::TESTER_TEST_LOG            => [
                    self::TESTER_TEST_LOG_SUMMARY => '',
                ],
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

    /**
     * @return $this
     */
    public static function motTestLog($testerId)
    {
        return self::create()
            ->routeParam('id', $testerId)
            ->appendRoutesAndParams(self::TESTER_TEST_LOG);
    }

    /**
     * @return $this
     */
    public static function motTestLogSummary($testerId)
    {
        return self::motTestLog($testerId)
            ->appendRoutesAndParams(self::TESTER_TEST_LOG_SUMMARY);
    }
}
