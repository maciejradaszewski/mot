<?php


namespace DvsaCommon\Model;


use DvsaCommon\Enum\SiteTypeCode;

class TypeOfVts
{

    /**
     * Returns an array of VTS type codes that are availible at creating/editing VTS type
     * @return array
     */
    public static function getPossibleVtsTypes()
    {
        return [
            SiteTypeCode::VEHICLE_TESTING_STATION,
            SiteTypeCode::AREA_OFFICE,
            SiteTypeCode::TRAINING_CENTRE,
        ];
    }
}