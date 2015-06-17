<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\VehicleUrlBuilder;

class Vm2073CertificateExpiryDateService
{
    private $vehicleId = 4;
    private $expectedExpiryDate = '2014-05-03';
    private $expectedEarliestTestDateForPostdatingExpiryDate = '2014-04-02';

    private $testerUsername;

    private $result;

    public function __construct($testerUsername)
    {
        $this->testerUsername = $testerUsername;
    }

    public function execute()
    {
        $result = TestShared::execCurlForJsonFromUrlBuilder(
            new \MotFitnesse\Util\CredentialsProvider($this->testerUsername, TestShared::PASSWORD),
            VehicleUrlBuilder::vehicle($this->vehicleId)->testExpiryCheck()
        );

        $this->result = $result['data']['checkResult'];
    }

    public function result()
    {
        return TestShared::$lastInfo['http_code'];
    }

    public function actualExpiry()
    {
        return $this->result['expiryDate'];
    }
    public function preservationDate()
    {
        return $this->result['earliestTestDateForPostdatingExpiryDate'];
    }

    public function isEarlierThanTestDateLimit()
    {
        return (boolean)$this->result['isEarlierThanTestDateLimit'];
    }

    public function previousCertificate()
    {
        return $this->result['previousCertificateExists'];
    }

    public function setVehicleId($value)
    {
        $this->vehicleId = $value;
    }

    public function setExpectedExpiryDate($value)
    {
        $this->expectedExpiryDate = $value;
    }

    public function setExpectedEarliestTestDateForPostdatingExpiryDate($value)
    {
        $this->expectedEarliestTestDateForPostdatingExpiryDate = $value;
    }
}
