<?php

use MotFitnesse\Testing\Objects\MotTestCreate;
use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class MotTestNumberFind
{
    /** @var Tester1CredentialsProvider $credential */
    private $credential;
    private $motTestNumberGenerated;
    private $motTestIdGenerated;

    private $v5c;

    private $userV5C;
    private $useMOTTestNumber;
    private $useMOTTestId;

    private $result;
    private $siteId;

    public function __construct($v5c, $testerUsername, $siteId)
    {
        $this->v5c = $v5c;

        $this->siteId = $siteId;
        $this->credential = new CredentialsProvider($testerUsername, TestShared::PASSWORD);
    }

    public function beginTable()
    {
        $vehicleTestHelper = new VehicleTestHelper(FitMotApiClient::createForCreds($this->credential));
        $vehicleId = $vehicleTestHelper->generateVehicle();
        $vehicleTestHelper->generateV5c($vehicleId, $this->v5c);
        $motTestHelper = new MotTestHelper($this->credential);
        $this->motTestNumberGenerated = $motTestHelper
            ->createPassedTest(
                (new MotTestCreate())
                    ->vehicleId($vehicleId)
                    ->siteId($this->siteId)
            );

        $motTestData = $motTestHelper->getMotTest($this->motTestNumberGenerated);
        $this->motTestIdGenerated = $motTestData['id'];
    }

    public function execute()
    {
        $urlBuilder = UrlBuilder::motTestFindMotTestNumber()->queryParams(
            [
                'motTestId'     => $this->useMOTTestId ? $this->motTestIdGenerated : null,
                'motTestNumber' => $this->useMOTTestNumber ? $this->motTestNumberGenerated : null,
                'v5c'           => empty($this->userV5C) ? null : $this->v5c
            ]
        );

        $this->result = TestShared::execCurlForJsonFromUrlBuilder($this->credential, $urlBuilder);
    }

    public function reset()
    {
        $this->result = null;
    }

    public function retrievedMOTTestNumber()
    {
        return TestShared::resultIsSuccess($this->result) ? $this->result : '';
    }

    public function errorMessage()
    {
        return TestShared::errorMessages($this->result);
    }

    public function setUseMOTTestId($value)
    {
        $this->useMOTTestId = $this->trueFalseOrException($value);
    }

    public function setUseMOTTestNumber($value)
    {
        $this->useMOTTestNumber = $this->trueFalseOrException($value);
    }

    public function setV5C($value)
    {
        $this->userV5C = $value;
    }

    private function trueFalseOrException($value)
    {
        $lowerValue = strtolower($value);

        if($lowerValue !== 'true' && $lowerValue !== 'false')
        {
            throw new  \InvalidArgumentException('Expecting true/false, received: ' . $value);
        }

        return $lowerValue === 'true';
    }
}
