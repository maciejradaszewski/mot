<?php

namespace DvsaCommon\UrlBuilder;

/**
 * Url Builder for web for VehicleTestingStation
 */
class VehicleTestingStationUrlBuilderWeb extends AbstractUrlBuilder
{
    const MAIN = '/vehicle-testing-station';
    const BY_ID = '/:id';

    const EDIT = '/edit';
    const CONTACT_DETAILS = '/contact-details';

    const TESTING_FACILITIES = '/testing-facilities';
    const TESTING_FACILITIES_CONFIRM = '/confirmation';
    const SITE_DETAILS = '/site-details';
    const SITE_DETAILS_CONFIRM = '/confirmation';

    const RISK_ASSESSMENT = '/risk-assessment';
    const ADD_RISK_ASSESSMENT = '/add-risk-assessment';
    const CANCEL_ADD_RISK_ASSESSMENT = '/add-risk-assessment/cancel';
    const ADD_RISK_ASSESSMENT_CONFIRMATION = '/confirmation';


    const CREATE = '/create';
    const CREATE_CONFIRM = '/confirmation';


    protected $routesStructure
        = [
            self::MAIN   => [
                self::BY_ID          => [
                    self::EDIT            => '',
                    self::CONTACT_DETAILS => '',
                    self::TESTING_FACILITIES => [
                        self::TESTING_FACILITIES_CONFIRM => '',
                    ],
                    self::SITE_DETAILS => [
                        self::SITE_DETAILS_CONFIRM => '',
                    ],
                    self::RISK_ASSESSMENT => '',
                    self::ADD_RISK_ASSESSMENT => [
                        self::ADD_RISK_ASSESSMENT_CONFIRMATION => '',
                    ],
                    self::CANCEL_ADD_RISK_ASSESSMENT => '',
                ],
                self::CREATE => [
                    self::CREATE_CONFIRM => '',
                ],
            ],
        ];

    private static function main()
    {
        return self::of()->appendRoutesAndParams(self::MAIN);
    }

    public static function byId($id)
    {
        return self::main()
            ->appendRoutesAndParams(self::BY_ID)
            ->routeParam('id', $id);
    }

    public static function edit($siteId)
    {
        return self::byId($siteId)->appendRoutesAndParams(self::EDIT);
    }

    public static function contactDetails($siteId)
    {
        return self::byId($siteId)->appendRoutesAndParams(self::CONTACT_DETAILS);
    }

    public static function testingFacilities($siteId)
    {
        return self::byId($siteId)->appendRoutesAndParams(self::TESTING_FACILITIES);
    }

    public static function testingFacilitiesConfirmation($siteId)
    {
        return self::testingFacilities($siteId)
            ->appendRoutesAndParams(self::TESTING_FACILITIES_CONFIRM);
    }

    public static function siteDetails($siteId)
    {
        return self::byId($siteId)->appendRoutesAndParams(self::SITE_DETAILS);
    }

    public static function create()
    {
        return self::main()->appendRoutesAndParams(self::CREATE);
    }

    public static function createConfirm()
    {
        return self::create()
            ->appendRoutesAndParams(self::CREATE_CONFIRM);
    }

    public static function siteDetailsConfirm($siteId)
    {
        return self::siteDetails($siteId)
            ->appendRoutesAndParams(self::SITE_DETAILS_CONFIRM);
    }

    public static function viewSiteRiskAssessment($siteId)
    {
        return self::byId($siteId)
            ->appendRoutesAndParams(self::RISK_ASSESSMENT);
    }

    public static function addSiteRiskAssessment($siteId)
    {
        return self::byId($siteId)->appendRoutesAndParams(self::ADD_RISK_ASSESSMENT);
    }

    public static function addSiteRiskAssessmentConfirm($siteId)
    {
        return self::addSiteRiskAssessment($siteId)
            ->appendRoutesAndParams(self::ADD_RISK_ASSESSMENT_CONFIRMATION);
    }

    public static function cancelSiteRiskAssessment($siteId)
    {
        return self::byId($siteId)
            ->appendRoutesAndParams(self::CANCEL_ADD_RISK_ASSESSMENT)
        ;
    }
}
