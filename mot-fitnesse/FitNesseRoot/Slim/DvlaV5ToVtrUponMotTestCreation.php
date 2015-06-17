<?php

use MotFitnesse\Testing\Objects\MotTestCreate;
use MotFitnesse\Util\Tester1CredentialsProvider;
use MotFitnesse\Util\VehicleUrlBuilder;
use MotFitnesse\Util\TestShared;

require_once 'configure_autoload.php';

/**
 * Tests if Dvla V5 data is properly transferred to VTR when MOT test is created
 */
class DvlaV5ToVtrUponMotTestCreation
{
    private $dvlaVehicleId;
    private $siteId;
    private $vehicleClass;
    /** @var \MotTestHelper */
    private $motTestHelper;

    public function __construct($testerUsername)
    {
        $this->motTestHelper = new \MotTestHelper(
            new \MotFitnesse\Util\CredentialsProvider(
                $testerUsername,
                TestShared::PASSWORD
            )
        );
    }

    private function getDvlaVehicle($id)
    {
        $url = VehicleUrlBuilder::dvlaVehicle($id);
        $result = TestShared::executeAndReturnResponseAsArrayFromUrlBuilder(
            new Tester1CredentialsProvider(),
            $url
        );

        return $result;
    }

    public function isVehicleCreated()
    {
        $dvlaVehicle = $this->getDvlaVehicle($this->dvlaVehicleId);

        $motCreateObj = (new MotTestCreate())
            ->dvlaVehicleId($this->dvlaVehicleId)
            ->siteId($this->siteId)
            ->vehicleClass($this->vehicleClass);
        $motTestId = $this->motTestHelper->createPassedTest($motCreateObj);

        $motTest = $this->motTestHelper->getMotTest($motTestId);
        $vtrVehicle = $motTest['vehicle'];

        return $vtrVehicle['vin'] === $dvlaVehicle['vin']
            && $vtrVehicle['cylinderCapacity'] === $dvlaVehicle['cylinderCapacity']
            && $vtrVehicle['colour']['code'] === $motCreateObj->primaryColour
            && $vtrVehicle['registration'] === $dvlaVehicle['registration'];

    }

    public function setDvlaVehicleId($value)
    {
        $this->dvlaVehicleId = $value;
    }


    public function setSiteId($value)
    {
        $this->siteId = $value;
    }

    public function setVehicleClass($value)
    {
        $this->vehicleClass = $value;
    }
}
