<?php

namespace IntegrationApi\OpenInterface\Mapper;

use DvsaEntities\Entity\MotTest;
use DvsaEntities\Entity\Vehicle;
use IntegrationApi\MotTestCommon\Mapper\AbstractMotTestMapper;

/**
 * Class OpenInterfaceMotTestMapper
 */
class OpenInterfaceMotTestMapper extends AbstractMotTestMapper
{
    public function toArray(MotTest $motTest)
    {
        $colour = $motTest->getPrimaryColour();
        $colour2 = $motTest->getSecondaryColour();

        return [
            'vrm'          => $motTest->getRegistration(),
            'make'         => $motTest->getMakeName(),
            'model'        => $motTest->getModelName(),
            'colourCode1'  => null === $colour ? null : $colour->getCode(),
            'colour1'      => null === $colour ? null : $colour->getName(),
            'colourCode2'  => null === $colour2 ? null : $colour2->getCode(),
            'colour2'      => null === $colour2 ? null : $colour2->getName(),
            'odometer'     => $motTest->getOdometerReading()->getValue(),
            'odometerUnit' => $motTest->getOdometerReading()->getUnit(),
            'testNumber'   => $motTest->getNumber(),
            'testDate'     => $this->returnFormattedDateOrNull($motTest->getIssuedDate()),
            'expiryDate'   => $this->returnFormattedDateOrNull($motTest->getExpiryDate()),
            'vtsNumber'    => $motTest->getVehicleTestingStation()->getSiteNumber(),
            'vtsTelNo'     => $this->extractPhoneNumber($motTest->getVehicleTestingStation()),
        ];
    }

    public function pre1960VehicleWithNoMotTestToArray(Vehicle $vehicle) {

        $colour = $vehicle->getColour();
        $colour2 = $vehicle->getSecondaryColour();

        return [
            'vrm'          => $vehicle->getRegistration(),
            'make'         => $vehicle->getMakeName(),
            'model'        => $vehicle->getModelName(),
            'colourCode1'  => null === $colour ? null : $colour->getCode(),
            'colour1'      => null === $colour ? null : $colour->getName(),
            'colourCode2'  => null === $colour2 ? null : $colour2->getCode(),
            'colour2'      => null === $colour2 ? null : $colour2->getName(),
            'odometer'     => 1960,
            'odometerUnit' => 'M',
            'testNumber'   => '196019601960',
            'testDate'     => date('Y-01-01'),
            'expiryDate'   => date('Y-01-01', strtotime('+1 year')),
            'vtsNumber'    => 'PRE1960',
            'vtsTelNo'     => 'PRE1960'
        ];
    }
}
