<?php

use MotFitnesse\Testing\Objects\MotTestCreate;
use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\TestShared;

class Vm4497CreateMotTest
{
    private $login;
    private $vtsId;
    private $error;

    public function setLogin($value)
    {
        $this->login = $value;
    }

    public function setVtsId($value)
    {
        $this->vtsId = $value;
    }

    public function success()
    {
        $this->error = null;

        $testHelper = new MotTestHelper(new CredentialsProvider($this->login, TestShared::PASSWORD));
        $vehicleHelper = new VehicleTestHelper(FitMotApiClient::create($this->login, TestShared::PASSWORD));

        try {
            $vehicleId = $vehicleHelper->generateVehicle();
            $data = (new MotTestCreate())
                        ->vehicleId($vehicleId)
                        ->siteId($this->vtsId);

            $testHelper->createPassedTest($data);
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();

            return false;
        }
    }

    public function error()
    {
        return $this->error;
    }

    public function setUserInfo()
    {
    }
}
