<?php

require_once 'configure_autoload.php';
use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

class Vm13NumberOfTesterVts extends Vm12AndVm13Base
{
    protected function retrieveData()
    {
        return TestShared::execCurlForJsonFromUrlBuilder($this, (new UrlBuilder())->tester()->queryParam('userId', $this->userId));
    }

    public function numberOfVehicleTestingStations()
    {
        $jsonResult = $this->retrieveData();

        return count($jsonResult['data']['vtsSites']);
    }

    public function setInfoAboutUser()
    {
    }
}