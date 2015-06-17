<?php

namespace MotFitnesse\Testing\Vehicle;

use MotFitnesse\Util\RandomGenerator;
use MotFitnesse\Util\RetrieveCheckingHelper;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\VehicleUrlBuilder;

/**
 * Implementation of RetrieveCheckingHelper for Vehicle used in MOT Testing
 */
class VehicleHelper extends RetrieveCheckingHelper
{
    public function __construct($vehicleId)
    {
        parent::__construct($vehicleId);
    }

    protected function retrieve($vehicleId)
    {
        return TestShared::executeAndReturnResponseAsArrayFromUrlBuilder(
            $this->credentialsProvider, VehicleUrlBuilder::vehicle($vehicleId)
        );
    }

    public static function generateVin()
    {

        return RandomGenerator::generateRandomString(17);
    }

    public static function generateVrm()
    {
        return RandomGenerator::generateRandomString(7);
    }
}
