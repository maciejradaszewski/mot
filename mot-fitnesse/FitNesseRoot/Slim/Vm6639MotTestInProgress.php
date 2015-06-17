<?php

use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

class Vm6639MotTestInProgress
{
    private $vtsId;
    private $userId;
    private $username;

    public function setVtsId($vtsId)
    {
        $this->vtsId = $vtsId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
    public function setUsername($username)
    {
        $this->username = $username;
    }

    private function prepareCurlHandle()
    {
        $url = (new UrlBuilder())->personMotTestInProgress()->routeParam('id', $this->userId)->toString();

        return TestShared::prepareCurlHandleToSendJson($url, TestShared::METHOD_GET, null, $this->username, TestShared::PASSWORD);
    }

    public function testInProgress()
    {
        $curlHandle = $this->prepareCurlHandle();

        $this->result = TestShared::execCurlForJson($curlHandle);
        return $this->result['data']['inProgressTestNumber'];
    }

    private function createVehicle()
    {
        $vehicleTestHelper = (new VehicleTestHelper(FitMotApiClient::create($this->username, TestShared::PASSWORD)));

        return $vehicleTestHelper->generateVehicle();
    }

    public function startNewMotTest()
    {
        $credentialsProvider = new \MotFitnesse\Util\CredentialsProvider($this->username, TestShared::PASSWORD);
        $motTestHelper = new MotTestHelper($credentialsProvider);

        $motTest = $motTestHelper->createMotTest(
            $this->createVehicle(),
            null,
            $this->vtsId
        );

        return $motTest['motTestNumber'];
    }
}
