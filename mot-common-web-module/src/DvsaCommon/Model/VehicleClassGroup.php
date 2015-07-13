<?php

namespace DvsaCommon\Model;

use DvsaCommon\Enum\VehicleClassCode;
use DvsaCommon\Enum\VehicleClassGroupCode;

final class VehicleClassGroup
{
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
            VehicleClassCode::CLASS_1,
            VehicleClassCode::CLASS_2,
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
            VehicleClassCode::CLASS_3,
            VehicleClassCode::CLASS_4,
            VehicleClassCode::CLASS_5,
            VehicleClassCode::CLASS_7,
        ];
    }

    public static function isGroup($vehicleClass, $group)
    {
        return in_array($vehicleClass, self::getClassesForGroup($group));
    }

    public static function getClassesForGroup($group) {
        if ($group == VehicleClassGroupCode::BIKES) {
            return self::getGroupAClasses();
        } elseif ($group == VehicleClassGroupCode::CARS_ETC) {
            return self::getGroupBClasses();
        }

        throw new \InvalidArgumentException("Unknown group");
    }
}
