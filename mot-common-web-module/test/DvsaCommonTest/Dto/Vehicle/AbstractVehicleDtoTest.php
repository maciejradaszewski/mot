<?php

namespace DvsaCommonTest\Dto\Vehicle;

use DvsaCommon\Dto\Common\ColourDto;
use DvsaCommon\Dto\Vehicle\AbstractVehicleDto;
use DvsaCommon\Dto\Vehicle\ModelDetailDto;
use DvsaCommon\Dto\Vehicle\VehicleParamDto;
use DvsaCommon\Dto\VehicleClassification\VehicleClassDto;

/**
 * Unit test for Class AbstractVehicleDto
 *
 * @package DvsaCommonTest\Dto\Vehicle
 */
abstract class AbstractVehicleDtoTest extends \PHPUnit_Framework_TestCase
{
    protected function fillAndCheckCommonGetters(AbstractVehicleDto $vehicleDto)
    {
        $expectRegistration = 'regValue';
        $expectVin = 'vinValue';
        $expectVehicleClassDto = (new VehicleClassDto())->setId(1);

        $expectManufactureDate = new \DateTime('2011-12-13');
        $expectFirstUsedDate = new \DateTime('2008-09-10');
        $expectFirstRegDate = new \DateTime('2007-08-09');
        $expectIsNewAtReg = true;

        $expectedMakeName = "Renault";
        $expectedModelName = "Captur";
        $expectFuelTypeDto = (new VehicleParamDto())->setId(1);
        $expectBodyTypeDto = (new VehicleParamDto())->setId(2);
        $expectTransmissionTypeDto = (new VehicleParamDto())->setId(3);

        $expectSeating = 999;

        $expectCylinder = 888;
        $expectEngineNr = 'ENGINE_NR_1234';

        $expectColour = (new ColourDto())->setCode(1);
        $expectColour2 = (new ColourDto())->setCode(2);

        //  --  fill object with expected values    --
        $vehicleDto
            ->setId(1)
            ->setRegistration($expectRegistration)
            ->setVin($expectVin)
            ->setVehicleClass($expectVehicleClassDto)

            ->setManufactureDate($expectManufactureDate)
            ->setFirstUsedDate($expectFirstUsedDate)
            ->setFirstRegistrationDate($expectFirstRegDate)
            ->setIsNewAtFirstReg($expectIsNewAtReg)

            ->setMakeName($expectedMakeName)
            ->setModelName($expectedModelName)
            ->setFuelType($expectFuelTypeDto)
            ->setBodyType($expectBodyTypeDto)
            ->setTransmissionType($expectTransmissionTypeDto)

            ->setSeatingCapacity($expectSeating)

            ->setCylinderCapacity($expectCylinder)
            ->setEngineNumber($expectEngineNr)

            ->setColour($expectColour)
            ->setColourSecondary($expectColour2);

        //  --  check   --
        $this->assertEquals($expectRegistration, $vehicleDto->getRegistration());
        $this->assertEquals($expectVin, $vehicleDto->getVin());
        $this->assertSame($expectVehicleClassDto, $vehicleDto->getVehicleClass());

        $this->assertSame($expectManufactureDate, $vehicleDto->getManufactureDate());
        $this->assertSame($expectFirstUsedDate, $vehicleDto->getFirstUsedDate());
        $this->assertSame($expectFirstRegDate, $vehicleDto->getFirstRegistrationDate());
        $this->assertEquals($expectIsNewAtReg, $vehicleDto->isNewAtFirstReg());

        $this->assertSame($expectedMakeName, $vehicleDto->getMakeName());
        $this->assertSame($expectedModelName, $vehicleDto->getModelName());
        $this->assertSame($expectFuelTypeDto, $vehicleDto->getFuelType());
        $this->assertSame($expectBodyTypeDto, $vehicleDto->getBodyType());
        $this->assertSame($expectTransmissionTypeDto, $vehicleDto->getTransmissionType());

        $this->assertEquals($expectSeating, $vehicleDto->getSeatingCapacity());

        $this->assertEquals($expectCylinder, $vehicleDto->getCylinderCapacity());
        $this->assertEquals($expectEngineNr, $vehicleDto->getEngineNumber());

        $this->assertSame($expectColour, $vehicleDto->getColour());
        $this->assertSame($expectColour2, $vehicleDto->getColourSecondary());
    }
}
