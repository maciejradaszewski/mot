<?php

namespace DvsaCommon\Model;

use DvsaCommon\Enum\VehicleClassCode;

final class VehicleClassGroup
{
    const CLASS_PREFIX = 'class';

    /**
     * @param string $vehicleClass
     * @return bool
     */
    public static function isGroupA($vehicleClass)
    {
        return in_array($vehicleClass, self::getGroupAClasses());
    }

    /**
     * @return array
     */
    public static function getGroupAClasses()
    {
        return [
            self::CLASS_PREFIX . VehicleClassCode::CLASS_1,
            self::CLASS_PREFIX . VehicleClassCode::CLASS_2,
        ];
    }

    /**
     * @param string $vehicleClass
     * @return bool
     */
    public static  function isGroupB($vehicleClass)
    {
        return in_array($vehicleClass, self::getGroupBClasses());
    }

    /**
     * @return array
     */
    public static function getGroupBClasses()
    {
        return [
            self::CLASS_PREFIX . VehicleClassCode::CLASS_3,
            self::CLASS_PREFIX . VehicleClassCode::CLASS_4,
            self::CLASS_PREFIX . VehicleClassCode::CLASS_5,
            self::CLASS_PREFIX . VehicleClassCode::CLASS_7,
        ];
    }
}
