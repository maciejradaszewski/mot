<?php

namespace DvsaCommon\Model;

use DvsaCommon\Enum\FuelTypeCode;
use DvsaCommon\Enum\FuelTypeId;

class FuelTypeAndCylinderCapacity 
{
    /**
     * Return a list of fuel types which Cylinder Capacity is irrelevant to them
     * @return array
     */
    public static function getAllFuelTypeCodesWithOptionalCylinderCapacity()
    {
        return [
            FuelTypeCode::ELECTRIC,
            FuelTypeCode::FUEL_CELLS,
            FuelTypeCode::STEAM,
        ];
    }

    /**
     * Return array of fuel type ids which Cylinder Capacity is required for them
     *
     * @return array
     */
    public static function getAllFuelTypeCodesWithCompulsoryCylinderCapacity()
    {
        return array_diff(
            FuelTypeCode::getAll(),
            self::getAllFuelTypeCodesWithOptionalCylinderCapacity()
        );
    }

    /**
     * Return the list of fuel type ids which Cylinder Capacity is required for them in a string format
     * (Comma separated by default)
     *
     * @param string $delimiter
     * @return string
     */
    public static function getAllFuelTypeCodesWithCompulsoryCylinderCapacityAsString($delimiter = ',')
    {
        return implode(
            $delimiter,
            self::getAllFuelTypeCodesWithCompulsoryCylinderCapacity()
        );
    }

    /**
     * To check if CC is optional for the given fuel type code.
     *
     * @param FuelTypeCode::getAll() $fuelType
     * @return bool
     */
    public static function isCylinderCapacityOptionalForFuelTypeCode($fuelType)
    {
        return in_array($fuelType, self::getAllFuelTypeCodesWithOptionalCylinderCapacity(), true);
    }

    /**
     * To check if CC is required for the given fuel type code.
     *
     * @param FuelTypeCode::getAll() $fuelType
     * @return bool
     */
    public static function isCylinderCapacityCompulsoryForFuelTypeCode($fuelType)
    {
        return !self::isCylinderCapacityOptionalForFuelTypeCode($fuelType);
    }

}
