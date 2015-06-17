<?php

use MotFitnesse\Util\Tester1CredentialsProvider;
use MotFitnesse\Util\UrlBuilder;

class Vm4504RefuseToTest
{
    const SITE_A = "A";
    const SITE_B = "B";

    private $username;
    private $password = TestSupportHelper::CREDENTIAL_DEFAULT_PASSWORD;

    private $siteId;
    private $vehicleId;
    private $testerId;
    private $vehicleExaminerId;

    private $registeredAtSite;
    private $userRole;

    /** @var \ApiErrorException $exception */
    private $exception;
    /** @var  FitMotApiClient */
    private $client;

    private $reasonForRefusal;

    public function setRegisteredAtSite($value)
    {
        $this->registeredAtSite = $value;
    }

    public function setUserRole($value)
    {
        $this->userRole = $value;
    }

    public function beginTable()
    {
        $this->reasonForRefusal = $this->getReasonForRefusal();
    }

    public function success()
    {
        try {

            if ($this->userRole === "Tester") {
                $this->username = $this->testerId;
            } elseif ($this->userRole === "Vehicle Examiner") {
                $this->username = $this->vehicleExaminerId;
            }

            $this->client = FitMotApiClient::create($this->username, $this->password);

            $data = [
                'vehicleId' => $this->vehicleId,
                'rfrId'     => $this->reasonForRefusal['id'],
                'siteId'    => $this->siteId,
            ];

            $this->refuseTest($data);

        } catch (ApiErrorException $ex) {
            $this->exception = $ex;
        }

        return $this->isSuccess();
    }

    public function reset() {
        $this->exception = null;
    }

    private function isSuccess()
    {
        return !$this->exception;
    }

    public function errorMessages()
    {
        return $this->isSuccess() ? '' : $this->exception->getMessage();
    }

    private function getReasonForRefusal()
    {
        $credentialsProvider = new  Tester1CredentialsProvider();
        $client = FitMotApiClient::createForCreds($credentialsProvider);
        $reasons = $client->get((new UrlBuilder())->dataCatalog());
        $reasonsForRefusal = $reasons['reasonsForRefusal'];
        return array_shift($reasonsForRefusal);
    }

    private function refuseTest(array $data)
    {
        $this->client->post((new UrlBuilder())->motTestRefusal(), $data);
    }

    public function setVehicleId($vehicleId)
    {
        $this->vehicleId = $vehicleId;
    }

    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
    }

    public function setVehicleExaminerId($vehicleExaminerId)
    {
        $this->vehicleExaminerId = $vehicleExaminerId;
    }

    public function setTesterId($testerId)
    {
        $this->testerId = $testerId;
    }
}
