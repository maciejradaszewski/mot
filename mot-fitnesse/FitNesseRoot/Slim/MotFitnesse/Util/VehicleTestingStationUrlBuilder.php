<?php

namespace MotFitnesse\Util;

/**
 * Class VehicleTestingStationUrlBuilder
 *
 * @package MotFitnesse\Util
 */
class VehicleTestingStationUrlBuilder extends AbstractUrlBuilder
{
    const VEHICLE_TESTING_STATION_APPLICATION_START = '/vehicle-testing-station-application-start/:designatedManagerId';
    const VEHICLE_TESTING_STATION_APPLICATION = '/vehicle-testing-station-application[/:uuid]';
    const APPLICANT_DETAILS = '/applicant-details';
    const APPLICATION_SUMMARY = '/application-summary';
    const EVIDENCE_OF_USE = '/evidence-of-use';
    const PLANNING_PERMISSION = '/planning-permission';
    const PLANS_AND_DIMENSIONS = '/plans-and-dimensions';
    const TESTING_FACILITIES = '/testing-facilities';
    const VEHICLE_TESTING_STATION_DETAILS = '/vehicle-testing-station-details';
    const DOCUMENTS = '/documents';
    const STATUS = '/status';

    const VTS_BY_ID = '/vehicle-testing-station[/:id]';
    const VTS_BY_SITE_NR = '/site/:sitenumber';
    const VTS_TEST_IN_PROGRESS = '/test-in-progress';
    const VTS_DEFAULT_BRAKE_TESTS = '/default-brake-tests';

    const VTS_CONTACT = '/contact[/:contactId]';
    const VTS_CONTACT_UPDATE = '/update';

    const SEARCH = '/vehicle-testing-station-search/[:search]';

    const SITE = '/site[/:siteId]';

    protected $routesStructure = [
        self::SITE                                      => '',
        self::VEHICLE_TESTING_STATION_APPLICATION_START => '',
        self::VEHICLE_TESTING_STATION_APPLICATION       => [
            self::APPLICANT_DETAILS               => '',
            self::APPLICATION_SUMMARY             => '',
            self::EVIDENCE_OF_USE                 => '',
            self::PLANNING_PERMISSION             => '',
            self::PLANS_AND_DIMENSIONS            => '',
            self::TESTING_FACILITIES              => '',
            self::VEHICLE_TESTING_STATION_DETAILS => '',
            self::DOCUMENTS                       => '',
            self::STATUS                          => '',
        ],
        self::VTS_BY_ID                                 => [
            self::VTS_BY_SITE_NR          => '',
            self::VTS_TEST_IN_PROGRESS    => '',
            self::VTS_DEFAULT_BRAKE_TESTS => '',
            self::VTS_CONTACT             => [
                self::VTS_CONTACT_UPDATE => '',
            ],
        ],
        self::SEARCH                                    => '',
    ];

    public static function vehicleTestingStationApplicationStart()
    {
        $urlBuilder = new VehicleTestingStationUrlBuilder();

        return $urlBuilder->appendRoutesAndParams(self::VEHICLE_TESTING_STATION_APPLICATION_START);
    }

    public static function vehicleTestingStationApplication()
    {
        $urlBuilder = new VehicleTestingStationUrlBuilder();

        return $urlBuilder->appendRoutesAndParams(self::VEHICLE_TESTING_STATION_APPLICATION);
    }

    public function applicantDetails()
    {
        return $this->appendRoutesAndParams(self::APPLICANT_DETAILS);
    }

    public function applicationSummary()
    {
        return $this->appendRoutesAndParams(self::APPLICATION_SUMMARY);
    }

    public function evidenceOfUse()
    {
        return $this->appendRoutesAndParams(self::EVIDENCE_OF_USE);
    }

    public function planningPermission()
    {
        return $this->appendRoutesAndParams(self::PLANNING_PERMISSION);
    }

    public function plansAndDimensions()
    {
        return $this->appendRoutesAndParams(self::PLANS_AND_DIMENSIONS);
    }

    public function testingFacilities()
    {
        return $this->appendRoutesAndParams(self::TESTING_FACILITIES);
    }

    public function vehicleTestingStationDetails()
    {
        return $this->appendRoutesAndParams(self::VEHICLE_TESTING_STATION_DETAILS);
    }

    public function documents()
    {
        return $this->appendRoutesAndParams(self::DOCUMENTS);
    }

    public function status()
    {
        return $this->appendRoutesAndParams(self::STATUS);
    }

    public function site()
    {
        return $this->appendRoutesAndParams(self::SITE);
    }

    public static function search()
    {
        return (new self())->appendRoutesAndParams(self::SEARCH);
    }

    public static function vtsById($id = null)
    {
        return self::of()->appendRoutesAndParams(self::VTS_BY_ID)
            ->routeParam('id', $id);
    }

    public static function vtsBySiteNr($siteNr = null)
    {
        return self::vtsById()
            ->appendRoutesAndParams(self::VTS_BY_SITE_NR)
            ->routeParam('sitenumber', $siteNr);
    }

    public static function testInProgress($id)
    {
        return self::vtsById($id)->appendRoutesAndParams(self::VTS_TEST_IN_PROGRESS);
    }

    public static function defaultBrakeTests($id)
    {
        return self::vtsById($id)->appendRoutesAndParams(self::VTS_DEFAULT_BRAKE_TESTS);
    }


    public static function contact($siteId, $contactId = null)
    {
        $url = self::vtsById($siteId)
            ->appendRoutesAndParams(self::VTS_CONTACT);

        if ((int)$contactId > 0) {
            $url->routeParam('contactId', $contactId);
        }

        return $url;
    }

    public static function contactUpdate($siteId, $contactId = null)
    {
        return self::contact($siteId, $contactId)
            ->appendRoutesAndParams(self::VTS_CONTACT_UPDATE);
    }
}
