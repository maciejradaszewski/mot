<?php

require_once 'configure_autoload.php';
use DvsaCommon\Enum\VehicleClassCode;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class Vm2974ChangeAuthorisationStatusOfInactiveTester
{
    private $jobUserName = 'tester-expiry-cron-job';
    private $jobPassword = TestShared::PASSWORD;

    private $curlUserName = 'ae';
    private $curlPassword = TestShared::PASSWORD;

    private $testerId = 1;
    private $vehicleClassCode = VehicleClassCode::CLASS_1;
    private $expectedStatusCodeAfterJobRun = 'a';
    private $origStatusCode = 'a';

    public function __construct()
    {
        $this->runJob();
    }

    public function setTesterId($testerId)
    {
        $this->testerId = $testerId;
    }

    public function setVehicleClassCode($vehicleClassCode)
    {
        $this->vehicleClassCode = $vehicleClassCode;
    }

    public function setExpectedStatusCodeAfterJobRun($expectedStatusCodeAfterJobRun)
    {
        $this->expectedStatusCodeAfterJobRun = $expectedStatusCodeAfterJobRun;
    }

    public function setOrigStatusCode($origStatusCode)
    {
        $this->origStatusCode = $origStatusCode;
    }

    public function setComment($comment)
    {
    }

    private function runJob()
    {
        $postData = ['username' => $this->jobUserName, 'password' => $this->jobPassword];

        $curlHandle = TestShared::prepareCurlHandleToSendJson(
            (new UrlBuilder())->testerExpiry()->toString(),
            TestShared::METHOD_POST,
            $postData,
            $this->jobUserName,
            $this->jobPassword
        );

        return TestShared::execCurlForJson($curlHandle);
    }

    private function getTesterAuthMotStatusData()
    {
        $curlHandle = TestShared::prepareCurlHandleToSendJson(
            (new UrlBuilder())->tester()
                ->routeParam('id', $this->testerId)
                ->toString(),
            TestShared::METHOD_GET,
            null,
            $this->curlUserName,
            $this->curlPassword
        );

        return TestShared::execCurlForJson($curlHandle);
    }

    public function isStatusCodeExpectedValue()
    {
        $result = $this->getTesterAuthMotStatusData();

        $authorisationsForTestingMot = $result['data']['authorisationsForTestingMot'];

        foreach ($authorisationsForTestingMot as $authorisationForTestingMot) {
            if ($authorisationForTestingMot['vehicleClassCode'] === $this->vehicleClassCode
                && $authorisationForTestingMot['statusCode'] === $this->expectedStatusCodeAfterJobRun
            ) {
                return true;
            }
        }

        return false;
    }
}
