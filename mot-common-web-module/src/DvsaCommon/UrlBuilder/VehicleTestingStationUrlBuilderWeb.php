<?php

namespace DvsaCommon\UrlBuilder;

/**
 * Url Builder for web for VehicleTestingStation
 */
class VehicleTestingStationUrlBuilderWeb extends AbstractUrlBuilder
{
    const MAIN = '/vehicle-testing-station';
    const BY_ID = '/:id';
    const BY_SITE_NUMBER = '/site/:sitenumber';
    const EDIT = '/edit';
    const CONTACT_DETAILS = '/contact-details';

    protected $routesStructure
        = [
            self::MAIN =>
                [
                    self::BY_ID          => [
                        self::EDIT   => '',
                        self::CONTACT_DETAILS => '',
                    ],
                    self::BY_SITE_NUMBER => '',
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

    public static function bySiteNumber($siteNumber)
    {
        return self::main()
            ->appendRoutesAndParams(self::BY_SITE_NUMBER)
            ->routeParam('sitenumber', $siteNumber);
    }


    public static function edit($siteId)
    {
        return self::byId($siteId)->appendRoutesAndParams(self::EDIT);
    }

    public static function contactDetails($siteId)
    {
        return self::byId($siteId)->appendRoutesAndParams(self::CONTACT_DETAILS);
    }
}
