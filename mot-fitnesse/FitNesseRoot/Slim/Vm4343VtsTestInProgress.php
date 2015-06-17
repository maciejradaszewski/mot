<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

/**
 * Checks list of organisation (Authorised Examiner) roles returned by api
 */
class Vm4343VtsTestInProgress
{
    private $result;
    private $vtsId;
    private $testIndex;

    public function setVtsId($vtsId)
    {
        $this->vtsId = $vtsId;
    }

    public function setTestIndex($testIndex)
    {
        $this->testIndex = $testIndex;
    }

    public function success()
    {
        $curlHandle = $this->prepareCurlHandle();

        $this->result = TestShared::execCurlForJson($curlHandle);

        return TestShared::resultIsSuccess($this->result);
    }

    private function prepareCurlHandle()
    {
        $url = (new UrlBuilder())->vehicleTestingStation()->routeParam('id', $this->vtsId)->vtsTestInProgress()
            ->toString();

        $username = TestShared::USERNAME_TESTER1;
        $password = TestShared::PASSWORD;

        return TestShared::prepareCurlHandleToSendJson($url, TestShared::METHOD_GET, null, $username, $password);
    }

    public function testerName()
    {
        return $this->getMotTestData()['testerName'];
    }

    public function vehicleRegisteredNumber()
    {
        return $this->getMotTestData()['vehicleRegisteredNumber'];
    }

    public function vehicleMakeName()
    {
        return $this->getMotTestData()['vehicleMake'];
    }


    public function vehicleModelName()
    {
        return $this->getMotTestData()['vehicleModel'];
    }

    private function getMotTestData()
    {
        $data = $this->result['data'];

        return $data[$this->testIndex];
    }
}
