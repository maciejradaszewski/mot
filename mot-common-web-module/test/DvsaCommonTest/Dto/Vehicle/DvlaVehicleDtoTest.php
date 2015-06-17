<?php

namespace DvsaCommonTest\Dto\Vehicle;

use DvsaCommon\Dto\Vehicle\DvlaVehicleDto;

/**
 * Unit test from class DvlaVehicleDtoTest
 *
 * @package DvsaCommonTest\Dto\Vehicle
 */
class DvlaVehicleDtoTest extends AbstractVehicleDtoTest
{
    public function testGetters()
    {
        $expectUnlanedWeight = 88888;
        $expectDesignedGrossWeight = 99999;

        //  --  fill object with expected values    --
        $vehicleDto = new DvlaVehicleDto();
        $vehicleDto
            ->setDesignedGrossWeight($expectDesignedGrossWeight)
            ->setUnladenWeight($expectUnlanedWeight);

        parent::fillAndCheckCommonGetters($vehicleDto);

        //  --  check   --
        $this->assertEquals($expectDesignedGrossWeight, $vehicleDto->getDesignedGrossWeight());
        $this->assertEquals($expectUnlanedWeight, $vehicleDto->getUnladenWeight());
        $this->assertTrue($vehicleDto->isDvla());
    }
}