<?php

namespace MotFitnesse\Testing\Objects;

use DvsaCommon\Enum\VehicleClassCode;
use MotTestHelper;
use DvsaCommon\Enum\ColourCode;

/**
 * @method MotTestCreate vehicleId($x)
 * @method MotTestCreate dvlaVehicleId($x)
 * @method MotTestCreate siteId($x)
 * @method MotTestCreate primaryColour($x)
 * @method MotTestCreate secondaryColour($x)
 * @method MotTestCreate vehicleClass($x)
 * @method MotTestCreate fuelType($x)
 * @method MotTestCreate odometerValue($x)
 * @method MotTestCreate odometerUnit($x)
 * @method MotTestCreate odometerResultType($x)
 * @method MotTestCreate motTestType($x)
 */
class MotTestCreate
{
    public $vehicleId;
    public $dvlaVehicleId;
    public $siteId;
    public $primaryColour = ColourCode::ORANGE;
    public $secondaryColour = ColourCode::BLACK;
    public $vehicleClass = VehicleClassCode::CLASS_4;
    public $fuelType = 'PE';
    public $odometerValue = 1234;
    public $odometerUnit = 'km';
    public $odometerResultType = 'OK';
    public $isRetest = MotTestHelper::TYPE_MOT_TEST_NORMAL;
    public $motTestType = 'NT';
    public $originalMotTestNumber = null;

    public function __call($name, $args)
    {
        $this->$name = $args[0];
        return $this;
    }
}
