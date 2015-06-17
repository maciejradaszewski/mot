<?php

namespace DvsaCommonTest\Dto\BrakeTest;

use DvsaCommon\Dto\BrakeTest\BrakeTestConfigurationClass1And2Dto;
use DvsaCommon\Enum\BrakeTestTypeCode;

/**
 * Tests for BrakeTestConfigurationClass1And2Dto
 */
class BrakeTestConfigurationClass1And2DtoTest extends \PHPUnit_Framework_TestCase
{
    /** @var BrakeTestConfigurationClass1And2Dto */
    private $dto;

    public function setUp()
    {
        $this->dto = new BrakeTestConfigurationClass1And2Dto();
    }

    public function testBrakeTestType()
    {
        $brakeTestType = BrakeTestTypeCode::ROLLER;

        $this->dto->setBrakeTestType($brakeTestType);
        $returnedValue = $this->dto->getBrakeTestType();

        $this->assertSame($brakeTestType, $returnedValue);
    }

    public function testVehicleWeightFront()
    {
        $vehicleWeightFront = '120';

        $this->dto->setVehicleWeightFront($vehicleWeightFront);
        $returnedValue = $this->dto->getVehicleWeightFront();

        $this->assertSame($vehicleWeightFront, $returnedValue);
    }

    public function testVehicleWeightRear()
    {
        $vehicleWeightRear = '130';

        $this->dto->setVehicleWeightRear($vehicleWeightRear);
        $returnedValue = $this->dto->getVehicleWeightRear();

        $this->assertSame($vehicleWeightRear, $returnedValue);
    }

    public function testRiderWeight()
    {
        $riderWeight = '100';

        $this->dto->setRiderWeight($riderWeight);
        $returnedValue = $this->dto->getRiderWeight();

        $this->assertSame($riderWeight, $returnedValue);
    }

    public function testSidecarWeight()
    {
        $sidecarWeight = '480';

        $this->dto->setSidecarWeight($sidecarWeight);
        $returnedValue = $this->dto->getSidecarWeight();

        $this->assertSame($sidecarWeight, $returnedValue);
    }

    public function testIsSidecarAttached()
    {
        $isSidecarAttached = true;

        $this->dto->setIsSidecarAttached($isSidecarAttached);
        $returnedValue = $this->dto->getIsSidecarAttached();

        $this->assertSame($isSidecarAttached, $returnedValue);
    }
}
