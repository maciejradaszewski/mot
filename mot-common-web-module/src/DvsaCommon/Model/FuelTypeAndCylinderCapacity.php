<?php

namespace DvsaCommon\Model;

use DvsaCommon\Enum\FuelTypeCode;

class FuelTypeAndCylinderCapacity 
{
    /**
     * Return a list of fuel types which Cylinder Capacity is irrelevant to them
     * @return array
     */
    public static function getAllFuelTypesWithOptionalCylinderCapacity()
    {
        return [
            FuelTypeCode::ELECTRIC,
            FuelTypeCode::FUEL_CELLS,
            FuelTypeCode::STEAM,
        ];
    }

    /**
     * Return a list of fuel types which Cylinder Capacity is required for them
     *
     * @param bool $commaSeparated to return array (by default) or comma separated string if set to true
     * @return array
     */
    public static function getAllFuelTypesWithCompulsoryCylinderCapacity($commaSeparated = false)
    {
        $fuelTypes = array_diff(
            FuelTypeCode::getAll(),
            self::getAllFuelTypesWithOptionalCylinderCapacity()
        );

        if ($commaSeparated) {
            $fuelTypes = implode(',',$fuelTypes);
        }

        return $fuelTypes;
    }

    /**
     * To check if CC is optional for the given fuel type.
     *
     * @param FuelTypeCode::getAll() $fuelType
     * @return bool
     */
    public static function isCylinderCapacityOptionalForFuelType($fuelType)
    {
        return in_array($fuelType, self::getAllFuelTypesWithOptionalCylinderCapacity(), true);
    }

    /**
     * To check if CC is required for the given fuel type.
     *
     * @param FuelTypeCode::getAll() $fuelType
     * @return bool
     */
    public static function isCylinderCapacityCompulsoryForFuelType($fuelType)
    {
        return !self::isCylinderCapacityOptionalForFuelType($fuelType);
    }

}
