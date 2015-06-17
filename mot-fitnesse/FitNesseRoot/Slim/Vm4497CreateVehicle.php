<?php

use MotFitnesse\Util\TestShared;

class Vm4497CreateVehicle
{
    private $login;
    private $error;

    public function setLogin($value)
    {
        $this->login = $value;
    }

    public function success()
    {
        $this->error = null;
        $helper = new VehicleTestHelper(FitMotApiClient::create($this->login, TestShared::PASSWORD));

        try {
            $helper->generateVehicle();
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
