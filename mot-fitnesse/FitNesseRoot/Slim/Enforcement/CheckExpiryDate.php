<?php

use MotFitnesse\Testing\Objects\MotTestCreate;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\VehicleUrlBuilder;

/**
 * Class Enforcement_CheckExpiryDate
 */
class Enforcement_CheckExpiryDate
{
    private $vehicleId = 110;
    private $siteId = 1;

    private $testType;
    private $testPass;
    private $expectedExpiryDate;

    private $credential;

    /** @var \MotTestHelper */
    private $motTestHelper;

    private $vehicleExaminerUsername;

    public function __construct($vehicleExaminerUsername)
    {
        $this->vehicleExaminerUsername = $vehicleExaminerUsername;
    }

    public function result()
    {
        $this->credential = new \MotFitnesse\Util\CredentialsProvider(
            $this->vehicleExaminerUsername,
            TestShared::PASSWORD
        );

        $this->motTestHelper = new \MotTestHelper($this->credential);

        $motTest = (new MotTestCreate())
            ->vehicleId($this->vehicleId)
            ->siteId($this->siteId)
            ->motTestType($this->testType);

        if ($this->testPass === 'true') {
            $this->motTestHelper->createPassedTest($motTest);
        }

        $curlHandle = curl_init(
            VehicleUrlBuilder::vehicle($this->vehicleId)->testExpiryCheck()->toString()
        );

        TestShared::SetupCurlOptions($curlHandle);
        TestShared::setAuthorizationInHeaderForUser(
            $this->credential->username,
            $this->credential->password,
            $curlHandle
        );

        return TestShared::executeAndReturnStatusCodeWithAnyErrors($curlHandle);
    }

    public function expiryDateCheck()
    {
        $result = TestShared::execCurlForJsonFromUrlBuilder(
            new \MotFitnesse\Util\Tester1CredentialsProvider(),
            VehicleUrlBuilder::vehicle($this->vehicleId)->testExpiryCheck()
        );

        $expectedExpiryDate = $this->expectedExpiryDate;
        if ($this->expectedExpiryDate == 'now+1y-1d') {
            $dt = new \DateTime('now');
            $dt->add(new \DateInterval('P1Y'));
            $dt->sub(new \DateInterval('P1D'));

            $expectedExpiryDate = $dt->format('Y-m-d');
        }

        return $result['data']['checkResult']['expiryDate'] === $expectedExpiryDate;
    }

    public function setTestType($value)
    {
        $this->testType = $value;
    }

    public function setTestPass($value)
    {
        $this->testPass = $value;
    }

    public function setSiteId($value)
    {
        $this->siteId = $value;
    }

    public function setVehicleId($value)
    {
        $this->vehicleId = $value;
    }

    public function setExpectedExpiryDate($value)
    {
        $this->expectedExpiryDate = trim($value);
    }

    public function setInfoAboutResult($value)
    {
    }
}
